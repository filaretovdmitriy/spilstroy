<?php

use yii\helpers\Html;
use app\modules\icms\assets\IcmsAsset;

IcmsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>ICMS<?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class='content_wrapper' style="width: 100%">
            <div class='content'>

                <?= $content ?>
                
                <div class='push'></div>
            </div>
            <div class='content_footer'>
                <span>Impresio v<?= Yii::$app->version ?>. Система управления сайтом.</span>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
