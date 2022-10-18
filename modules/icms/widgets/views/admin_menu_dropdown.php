<?php

use yii\helpers\Html;
?>

<?php if (count($adminMenu) > 1) { ?>
    <div class='add'>
        <span>Добавить</span>
        <div class='subnav'>
            <ul>
                <?php
                foreach ($adminMenu as $menuElem) {
                    if (empty($menuElem->parentName) === false && isset($params[$menuElem->parentName]) === true) {
                        $route = [$menuElem->route, $menuElem->parentName => $params[$menuElem->parentName]];
                    } else {
                        $route = [$menuElem->route];
                    }
                    ?>
                    <li><?= Html::a($menuElem->title, $route) ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>
<?php } else {
    if (empty($adminMenu[0]->parentName) === false && isset($params[$adminMenu[0]->parentName]) === true) {
        $route = [$adminMenu[0]->route, $adminMenu[0]->parentName => $params[$adminMenu[0]->parentName]];
    } else {
        $route = [$adminMenu[0]->route];
    }
    ?>
    <a class="add add-single" href="<?= \yii\helpers\Url::to($route) ?>">
        <span>Добавить</span>
    </a>
<?php } ?>
