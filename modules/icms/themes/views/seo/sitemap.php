<?php

use yii\helpers\Html;
use app\modules\icms\widgets\SiteMapForm;
?>
<form method="POST">
    <?=
    SiteMapForm::widget([
        'models' => [
            app\models\Tree::class,
            app\models\CatalogCategorie::class,
            app\models\Catalog::class,
            app\models\Content::class,
            app\models\GalleryCategorie::class,
        ]
    ])
    ?>
    
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'save', 'flash' => 'message']) ?>
    <?= \app\modules\icms\widgets\NotificationGritter::widget(['preset' => 'error', 'flash' => 'error']) ?>
    <fieldset>
        <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>
    </fieldset>
    <div class="clear"></div>
    <div class="col-100">
        <div class="action_buttons">
            <a class="back">Назад</a>
            <?= Html::submitButton('Сгенерировать', ['class' => 'save', 'name' => 'save-button']) ?>
        </div>
    </div>
</form>