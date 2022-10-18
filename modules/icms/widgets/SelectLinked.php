<?php


namespace app\modules\icms\widgets;


use app\assets\FancyBoxAsset;
use app\components\db\ActiveRecord;
use app\modules\icms\widgets\drop_down_list\DropDownList;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\widgets\InputWidget;

class SelectLinked extends InputWidget
{

    public $linkModel;

    public function run()
    {
        if (new $this->linkModel() instanceof ActiveRecord === false) {
            throw new BadRequestHttpException();
        }
        $items = $this->linkModel::getNamesAsArray('name', ['name' => SORT_ASC]);

        $options = $this->options;
        $selectOptions = ArrayHelper::remove($options, 'selectOptions', []);
        Html::addCssClass($selectOptions, 'js-select-linked-select');

        $select = $this->field->widget(DropDownList::class, [
            'id' => $this->getId() . '-selected-linked',
            'items' => $items,
            'options' => $selectOptions,
        ])->label(false);

        $button = Html::button('+', [
            'class' => 'button',
            'id' => $this->getId(),
            'data-model' => $this->linkModel,
            'data-target' => '#' . $this->getId() . '-selected-linked',
        ]);

        Html::addCssClass($options, 'select-linked-block');

        $this->_registerJs();

        return Html::tag('div', $select . $button, $options);
    }

    private function _registerJs()
    {
        if (\Yii::$app->getRequest()->isPjax === true) {
            return;
        }

        FancyBoxAsset::register($this->getView());


        $js = <<<JS
$('#{$this->id}').on('click', function() {
    let button = $(this);
    
    let template = `
<div class="js-select-link-block select-linked-popup">
    <h3>Добавление значения</h3>
    <fieldset>
        <input type="text" class="form-control js-select-link-value" placeholder="Введите новое значение">
    </fieldset>
    <button class="button js-select-link-add" data-model="\${button.data('model')}" data-target="\${button.data('target')}">Добавить</button>
</div>
`;
    $.fancybox.open(template, {"openEffect":"none","closeEffect":"none","helpers":{"overlay":{"locked":false}},"tpl":{"error":"<p class=\"fancybox-error\">Ошибка загрузки контента</p>","closeBtn":"<a title=\"Закрыть\" class=\"fancybox-item fancybox-close\" href=\"javascript:;\"></a>","next":"<a title=\"Следующее\" class=\"fancybox-nav fancybox-next\" href=\"javascript:;\"><span></span></a>","prev":"<a title=\"Предыдущее\" class=\"fancybox-nav fancybox-prev\" href=\"javascript:;\"><span></span></a>"}});
});

$(document).on('click', '.js-select-link-add', function() {
    let button = $(this);
    let value = button.closest('.js-select-link-block').find('.js-select-link-value').val();
    if (value.length === 0) {
        return;
    }
    let select = $(button.data('target'));
    $.post('/icms/ajax/select-link-add', {model: button.data('model'), value: value}, (data) => {
        $.fancybox.close();
        select.find('option').not('[value=""]').remove();
        
        for (let index in data.items) {
            select.append(`<option value="\${data.items[index].id}">\${data.items[index].value}</option>`);
        }
        
        select.val(data.id).trigger('change');
    }, 'json');
});
JS;

        $this->getView()->registerJs($js);
    }

}
