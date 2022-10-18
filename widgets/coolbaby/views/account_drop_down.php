<?php

use yii\helpers\Url;
?>
<div class="btn-group">
    <a href="#" title="Аккаунт" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><span class="icon icon-lg flaticon-business137"></span><span class="drop-title">Аккаунт</span></a>
    <ul class="dropdown-menu" role="menu">
        <?php if (Yii::$app->user->isGuest) { ?>
            <li><a href="<?= Url::to(['account/login']) ?>">Войти</a></li>
            <li><a href="<?= Url::to(['account/registration']) ?>">Регистрация</a></li>
        <?php } else { ?>
            <li><a href="<?= Url::to(['account/account']) ?>">Личный кабинет</a></li>
            <li><a href="<?= Url::to(['account/wishlist']) ?>">Список желаемого</a></li>
            <li class="divider"></li>
            <li><a href="<?= Url::to(['account/logout']) ?>">Выход</a></li>
        <?php } ?>
    </ul>
</div>