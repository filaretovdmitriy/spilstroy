<?php
use yii\helpers\Html;
use app\modules\icms\assets\IcmsAsset;

/* @var $this \yii\web\View */
/* @var $content string */

$icmsAsset = IcmsAsset::register($this);
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
<?php $this->beginBody();
?>


<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>