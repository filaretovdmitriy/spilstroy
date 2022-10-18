<?php

namespace app\modules\icms\widgets;

use yii\base\Widget;

class RelatedGoodTab extends Widget
{

    public $model;

    public function init()
    {
        $removeUrl = \yii\helpers\Url::to(['catalogajax/delete_related_good']);
        $js = <<<JS
$('#relaitedGoods').on('click', '.button', function() {
    if (!confirm('Вы дейтсвительно хотите удалить этот товар из соопутствующих?')) {
        return false;
    }
    
    goodId = $(this).data('good-id');
    relatedId = $(this).data('id');
    $('#preloader').show();
    $.post('{$removeUrl}', {goodId: goodId, relatedId: relatedId}, function(data) {
        if (data.success) {
            $.pjax.reload('#relaitedGoods');
        }
    }, 'json');
    return false;
});
JS;
        $this->view->registerJs($js);
    }

    public function run()
    {
        $relatedGoods = $this->model->relatedGoods;
        $relatingGoods = $this->model->relatingGoods;
        return $this->render('related_good_tab', [
                    'model' => $this->model,
                    'relatedGoods' => $relatedGoods,
                    'relatingGoods' => $relatingGoods
        ]);
    }

}
