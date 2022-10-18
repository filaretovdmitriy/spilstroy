<?php

use yii\helpers\Url;
?>
<ul class="list-inline right-topbar pull-right">
    <?php if (\Yii::$app->user->isGuest) { ?>
        <li><a href="<?= Url::to(['account/login']) ?>">Вход</a> | <a href="<?= Url::to(['account/registration']) ?>">Регистрация</a></li>
    <?php } else { ?>
        <li><a href="<?= Url::to(['account/account']) ?>">Аккаунт</a></li>
        <li><a href="<?= Url::to(['account/wishlist']) ?>">Список желаний</a></li>
    <?php } ?>
    <li><i class="search fa fa-search search-button"></i></li>
</ul>
