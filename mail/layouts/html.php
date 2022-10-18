<?php

use yii\helpers\Html;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <table cellspacing="0" cellpadding="10" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
            <tbody>
                <tr>
                    <td style="color:#4D90FE;font-size:22px;border-bottom: 2px solid #4D90FE;">
                        <?= Html::encode(Yii::$app->name) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $content ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>