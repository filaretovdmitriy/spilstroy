<?php

use yii\helpers\Html;
use app\modules\icms\assets\IcmsAsset;
use app\modules\icms\widgets\AdminMenuLeft;
use yii\helpers\Url;

IcmsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/icms/favicon/favicon-16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/icms/favicon/favicon-32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/icms/favicon/favicon-64.png" sizes="64x64">
        <link rel="icon" type="image/png" href="/icms/favicon/favicon-128.png" sizes="128x128">
        <?= Html::csrfMetaTags() ?>
        <title>ICMS<?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>
        <div class="sidebar_wrapper">
            <div class="sidebar">
                <a class="logo" href="/icms" title="Impresio cms v<?= Yii::$app->version ?>">
                    <img src="<?= IcmsAsset::path('img/logo.png') ?>">
                </a>
                <?= AdminMenuLeft::widget() ?>

                <a class="view_site" href="/" target="_blank">Перейти на сайт</a>

            </div>
            <div class="sidebar_footer">
                <div class="subnav_footer">
                    <ul>
                        <li>
                            <a href="<?= Url::to(['users/edit', 'id' => Yii::$app->user->id]) ?>">Редактировать профиль</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['users/edit_password', 'id' => Yii::$app->user->id]) ?>">Изменить пароль</a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['default/logout']) ?>">Выйти</a>
                        </li>
                    </ul>
                </div>
                <div class="user">
                    <i class="user_settings"></i>
                    <span><?= Yii::$app->user->identity->name?:Yii::$app->user->identity->login ?></span>
                </div>
            </div>
        </div>
        <div class="content_wrapper">
            <div class="content">
                <?= \app\modules\icms\widgets\GreenLine::widget() ?>

                <?= $content ?>
                
                <div class="clear"></div>
                <div class="push"></div>
            </div>
            <div class="content_footer">
                <span>Impresio v<?= Yii::$app->version ?>. Система управления сайтом.</span>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
