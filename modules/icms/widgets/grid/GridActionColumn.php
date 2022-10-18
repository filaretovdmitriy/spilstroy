<?php

namespace app\modules\icms\widgets\grid;

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\icms\assets\IcmsAsset;

class GridActionColumn extends \yii\grid\Column
{

    public $delete = false;
    public $deleteUrl = null;
    public $deleteSafe = null;
    public $save = false;
    public $saveUrl = null;
    public $view = null;
    public $saveAll = true;
    public $deleteAll = false;

    public function init()
    {
        $isDelete = $this->delete;
        if ($this->delete instanceof \Closure) {
            $isDelete = true;
        }
        $isSave = $this->save;
        if ($this->save instanceof \Closure) {
            $isSave = true;
        }
        
        $this->deleteAll = $isDelete && $this->deleteAll;
        $this->saveAll = $isSave && $this->saveAll;

        $this->headerOptions['width'] = '60';

        if ($isSave || $isDelete) {
            $greedId = $this->grid->id;
            $gritterImageOk = IcmsAsset::path('img/gritter-ok.png');
            $gritterImageError = IcmsAsset::path('img/gritter-remove.png');
            $script = <<<JS
$('#{$greedId} tbody').on('change', 'tr input[type=text], input[type=checkbox], tr select, tr textarea', function() {
    $(this).closest('td').addClass('has-edit');
});

$('#{$greedId}').on('click', '.grid-active-icon-save', function(e){
    var id = $(this).data('id');
    model = $(this).data('model');

    var inputsText = $('#{$greedId} tr[data-key='+ id +'] input[type=text], #{$greedId} tr[data-key='+ id +'] textarea');
    var inputsSelect = $('#{$greedId} tr[data-key='+ id +'] select');
    var inputsCheckBox = $('#{$greedId} tr[data-key='+ id +'] input[type=checkbox]');

    var params = {id: id, model: model};
    inputsText.each(function() {
        input = $(this);
        params[input.attr('name')] = input.val();
    });
                    
    inputsSelect.each(function() {
        input = $(this);
        params[input.attr('name')] = input.val();
    });

    inputsCheckBox.each(function() {
        input = $(this);
        attr = input.attr('name');
        if (input.prop('checked')) {
            params[attr] = 1;
        } else {
            params[attr] = 0;
        }
    });

    var href = $(this).attr('href');

    $.post(href, params, function(data) {
        if (data.success) {
            $('#{$greedId} table tr[data-key='+ id +'] td').removeClass('has-edit');
            $.gritter.add({title: 'Сохранение', text: data.text, image: '{$gritterImageOk}'});
        } else {
            $.gritter.add({title: 'Ошибка', text: data.text, image: '{$gritterImageError}'});
        }
    }, 'json');

    return false;
});
$('#{$greedId}').on('click', '.grid-active-icon-delete', function(e){
    if (confirm('Удалить?') == false) {
        return false;
    }
    var id = $(this).data('id');
    model = $(this).data('model');
    var href = $(this).attr('href');
    var self = this;
    $.post(href, {id: id, model: model}, function(data){
        var pjax_id = $(self).closest('.pjax-wraper').attr('id');
        $.pjax.reload('#' + pjax_id);
    },'json');
    return false;
});
JS;
            \Yii::$app->view->registerJs($script);
        }
    }

    private function genarateButton($url, $key, $modelName, $iconClass, $title, $all = false)
    {
        return Html::a(
                        Html::tag('i', '', ['class' => 'active-icon ' . $iconClass]), $url, [
                    'class' => 'grid-action grid-' . ($all ? 'all-' : '') . $iconClass,
                    'data-id' => $key,
                    'data-model' => $modelName,
                    'title' => $title
                        ]
        );
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $buttonsText = [];

        $isDelete = $this->delete;
        $isSave = $this->save;
        $isView = $this->view;
        
        if ($isDelete instanceof \Closure) {
            $isDelete = call_user_func($isDelete, $model);
        }
        if ($isSave instanceof \Closure) {
            $isSave = call_user_func($isSave, $model);
        }
        if ($isView instanceof \Closure) {
            $isView = call_user_func($isView, $model);
        }


        if (is_null($isView) === false && $isView !== false) {
            $url = $isView;
            if (is_array($url) === true) {
                $routeLink = [array_shift($url)];

                foreach ($url as $urlKey => $value) {
                    if (is_string($urlKey) === true) {
                        $routeLink[$urlKey] = $model->{$value};
                    } else {
                        $routeLink[$value] = $model->{$value};
                    }
                }

                $url = $routeLink;
            } else {
                $url .= $key;
            }
            $buttonsText[] = $this->genarateButton($url, $key, get_class($model), 'active-icon-view', 'Просмотреть');
        }

        if ($isSave !== false) {
            $url = $this->saveUrl;
            if (is_null($url)) {
                $url = Url::to(['ajax/save_model']);
            }
            $buttonsText[] = $this->genarateButton($url, $key, get_class($model), 'active-icon-save', 'Сохранить');
        }

        $safe = false;
        if (!is_null($this->deleteSafe)) {
            $safe = (boolean) $model->{$this->deleteSafe};
        }

        if ($isDelete !== false && ($safe === false || \Yii::$app->user->can('developer'))) {
            $url = $this->deleteUrl;
            if (is_null($url)) {
                $url = Url::to(['ajax/delete_model']);
            }
            $buttonsText[] = $this->genarateButton($url, $key, get_class($model), 'active-icon-delete', 'Удалить');
        }
        return Html::tag('div', implode('&nbsp;', $buttonsText));
    }

    private function registerSaveAllJs()
    {
        $gritterImage = IcmsAsset::path('img/gritter-ok.png');
        $greedId = $this->grid->id;
        \Yii::$app->view->registerJs(<<<JS
$('#{$greedId} .grid-all-active-icon-save').on('click', function() {
    var edits = {};
    var href = $(this).attr('href');

    $('table tbody tr').each(function() {
        tr = $(this);
        key = tr.data('key');
        var trEdits = {};
        modelFormName = '';
                
        var inputsText = tr.find('td.has-edit input[type=text], td.has-edit textarea');
        var inputsSelect = tr.find('td.has-edit select');
        var inputsCheckBox = tr.find('td.has-edit input[type=checkbox]');
                
        inputsText.each(function() {
            input = $(this);
            elName = input.attr('name').split('[');
            attr = elName[1].replace(']', '');
            modelFormName = elName[0];
            trEdits[attr] = input.val();
        });

        inputsSelect.each(function() {
            input = $(this);
            elName = input.attr('name').split('[');
            attr = elName[1].replace(']', '');
            modelFormName = elName[0];
            trEdits[attr] = input.val();
        });

        inputsCheckBox.each(function() {
            input = $(this);
            elName = input.attr('name').split('[');
            attr = elName[1].replace(']', '');
            modelFormName = elName[0];
            if (input.prop('checked')) {
                trEdits[attr] = 1;
            } else {
                trEdits[attr] = 0;
            }
        });
                
        if (modelFormName !== '' && Object.keys(trEdits).length > 0) {
            edits[key] = {};
            edits[key][modelFormName] = {};
            edits[key][modelFormName] = trEdits;
        }
    });
    if (Object.keys(edits).length == 0) {
        $.gritter.add({title: 'Ошибка', text: 'Нет данных для сохранения'});
        return false;
    }
    $.post(href, {modelsName: $(this).data('model'), modelsAttributes: edits}, function(data) {
        $('#{$greedId} table td').removeClass('has-edit');
        $.gritter.add({title: 'Сохранение', text: data.text, image: '{$gritterImage}'});
    }, 'json');
    return false;
});
JS
        );
    }

    private function registerDeleteAllJs()
    {
        $greedId = $this->grid->id;
        \Yii::$app->view->registerJs(<<<JS
$('#{$greedId} .grid-all-active-icon-delete').on('click', function() {
    if (confirm('Удалить ВСЕ текущие записи?') == false) {
        return false;
    }
    var elem = $(this);
    model = elem.data('model');
    href = elem.attr('href');
    var deleteIds = [];
    elem.closest('table').find('tbody tr').each(function() {
        deleteIds.push($(this).data('key'));
    });

    $.post(href, {modelName: model, deleteIds: deleteIds}, function(date) {
        var pjax_id = $(elem).closest('.pjax-wraper').attr('id');
        $.pjax.reload('#' + pjax_id);
    }, 'json');
    return false;
});
JS
        );
    }

    public function renderHeaderCellContent()
    {
        $buttonsHtml = [];

        if ($this->saveAll) {
            $this->registerSaveAllJs();
            $buttonsHtml[] = $this->genarateButton('/icms/ajax/grid_view_save_all', false, $this->grid->modelName, 'active-icon-save', 'Сохранить все', true);
        }

        if ($this->deleteAll) {
            $this->registerDeleteAllJs();
            $buttonsHtml[] = $this->genarateButton('/icms/ajax/grid_view_delete_all', false, $this->grid->modelName, 'active-icon-delete', 'Удалить текущие', true);
        }
        return Html::tag('div', implode('&nbsp;', $buttonsHtml));
    }

}
