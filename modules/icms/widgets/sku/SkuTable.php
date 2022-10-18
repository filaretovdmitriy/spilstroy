<?php

namespace app\modules\icms\widgets\sku;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

class SkuTable extends Widget
{

    public $skuList;
    public $allCatalogSku;
    public $catalogModel;
    private $columns = [
        -3 => 'id',
        -1 => 'Общие',
        -4 => 'Изображение',
        0 => 'Не группированые'
    ];

    private function initColumns()
    {
        $gruopsId = [];
        foreach ($this->allCatalogSku as $catalogSku) {
            if (!in_array($catalogSku->props_groups_id, $gruopsId)) {
                $gruopsId[] = $catalogSku->props_groups_id;
            }
        }

        $this->columns += ArrayHelper::map(\app\models\PropsGroup::find()->andWhere(['id' => $gruopsId])->all(), 'id', 'name');
        $this->columns[-2] = '';
    }

    public function init()
    {
        $this->initColumns();
    }

    private function renderHead()
    {
        $html = '';
        foreach ($this->columns as $columnName) {
            $html .= Html::tag('td', $columnName);
        }
        return Html::tag('thead', Html::tag('tr', $html));
    }

    private function renderCell($columnId, &$props, &$values)
    {
        $html = [];
        foreach ($props as $prop) {
            if ($prop->props_groups_id != $columnId) {
                continue;
            }
            if ($prop->prop_type_id == 7) {
                $value = array_shift($values[$prop->id]) == 1 ? 'Да' : 'Нет';
            } else {
                $value = implode(', ', $values[$prop->id]);
            }
            $html[] = $prop->name . ': <b>' . $value . '</b>';
        }
        return Html::tag('td', implode('<br>', $html));
    }

    private function renderActionButtons(&$sku)
    {
        $html = [];
        $html[] = SkuModalImages::widget(['pjaxId' => $this->id . '-catalog-sku-table']);
        $html[] = Html::a(
                        Html::tag('i', '', ['class' => 'active-icon active-icon-view', 'title' => 'Быстрое редактирование']), ['catalogajax/sku', 'catalog_id' => $sku->catalog_id, 'sku_id' => $sku->id], [
                    'class' => 'sku-action-edit',
                    'data-pjax' => 0
                        ]
        );
        $html[] = Html::a(
                        Html::tag('i', '', ['class' => 'active-icon active-icon-images', 'title' => 'Изображения']), ['catalogajax/sku_images', 'sku_id' => $sku->id], [
                    'class' => 'sku-action-images',
                    'data-pjax' => 0,
                    'data-id' => $sku->id,
                        ]
                ) . SkuModal::widget(['edit' => true]);
        $html[] = Html::a(
                        Html::tag('i', '', ['class' => 'active-icon active-icon-delete']), ['catalogajax/delete_sku'], [
                    'class' => 'sku-action-delete',
                    'data-pjax' => 0,
                    'data-id' => $sku->id,
                    'title' => 'Удалить'
                        ]
        );
        return Html::tag('td', implode('&nbsp;', $html));
    }

    private function registerJsActions()
    {
        $js = <<<JS
$('.pjax-sku-wraper').on('click', '.sku-action-delete', function(e){
    if (confirm("Удалить торговое предложение?")) {
        var id = $(this).data('id');
        var href = $(this).attr('href');
        $.post(href, {id: id}, function(data){
            $.pjax.reload('#{$this->id}-catalog-sku-table');
            $.gritter.add({'title': 'Удалено', 'text': 'Торговое предложение удалено'});
        },'json');
    }
    return false;
});
JS;
        $this->view->registerJs($js);
    }

    private function renderBaseProps(&$sku)
    {
        $html = '';
        $html .= 'Артикул:<br><b>' . $sku->article . '</b><br>';
        if ($sku->price != 0) {
            $html .= 'Цена:<br><b>' . number_format($sku->price, 2, '.', ' ') . '</b><br>';
        } else {
            $html .= 'Цена:<br><b>' . number_format($this->catalogModel->price, 2, '.', ' ') . ' (Общая цена товара!)</b><br>';
        }
        $html .= 'Cтатус: <b>' . $sku->statusName . '</b>';
        return Html::tag('td', $html);
    }

    private function renderRows()
    {
        $html = '';
        if (count($this->skuList) === 0) {
            return Html::tag('tr', Html::tag('td', 'Не найдено торговых предложений для данного товара', ['colspan' => count($this->columns), 'style' => 'text-align: center']));
        }
        foreach ($this->skuList as $sku) {
            $values = $sku->getValuesAsArray();
            $rows = '';
            $props = $sku->props;
            foreach ($this->columns as $columnId => $columnName) {
                switch ($columnId) {
                    case -3:
                        $rows .= Html::tag('td', $sku->id);
                        break;
                    case -4:
                        $image = $sku->image;
                        if (!is_null($image)) {
                            $rows .= Html::tag('td', Html::img(\app\components\IcmsHelper::getResizePath($sku->image->getPath('image'), 120, 100, 2)));
                        } else {
                            $rows .= Html::tag('td');
                        }
                        break;
                    case -2:
                        $rows .= $this->renderActionButtons($sku);
                        break;
                    case -1:
                        $rows .= $this->renderBaseProps($sku);
                        break;
                    default :
                        $rows .= $this->renderCell($columnId, $props, $values);
                }
            }
            $html .= Html::tag('tr', $rows, ['data-sku-id' => $sku->id]);
        }
        return $html;
    }

    public function run()
    {
        $html = $this->renderHead();
        $html .= $this->renderRows();
        $this->registerJsActions();
        Pjax::begin(['id' => $this->id . '-catalog-sku-table', 'options' => ['class' => 'pjax-sku-wraper']]);
        echo Html::tag('table', $html, [
            'class' => 'table table-striped table-sku'
        ]);
        Pjax::end();
    }

}
