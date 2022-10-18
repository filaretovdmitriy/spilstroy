<?php

use yii\helpers\Url;
?>
<nav id="main-left-menu">
    <ul>
        <?php
        foreach ($adminMenu as $menuElem) {
            if ($menuElem->role != '' && !Yii::$app->user->can($menuElem->role)) {
                continue;
            }
            ?>
            <li class="<?= $menuElem->controller == Yii::$app->controller->id ? 'active' : '' ?>">
                <a href="<?= Url::to([$menuElem->route]) ?>">
                    <?php if ($menuElem->icon_class) { ?>
                        <i class='<?= $menuElem->icon_class ?>'></i>
                    <?php } else { ?>
                        <i class='icon_nav_structure'></i>
                    <?php } ?>
                    <span><?= $menuElem->title ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
