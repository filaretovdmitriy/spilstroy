<?php

use yii\helpers\Url;
?>
<!-- Main menu -->
<div class="navbar-main-menu-outer hidden-xs">
    <div class="container">
        <dl class="navbar-main-menu">
            <dt class="item"><a href="/" class="btn-main"><span class="icon icon-xl flaticon-home113"></span></a></dt>
            <dd></dd>
            <?php foreach ($categories as $categorie) { ?>
                <dt class="item">
                    <a href="<?= Url::to(['site/catalog_categorie', 'catalog_categorie_alias' => $categorie['alias']]) ?>" class="btn-main"><?= $categorie['name'] ?></a>
                </dt>
                <?php if (isset($categorie['childs'])) { ?>
                    <dd class="item-content content-small">
                        <div class="megamenuClose"></div>
                        <ul class="row-list">
                            <?php foreach ($categorie['childs'] as $child) { ?>
                                <li><a href="<?= Url::to(['site/catalog_categorie', 'catalog_categorie_alias' => $child['alias']]) ?>"><?= $child['name'] ?></a></li>
                            <?php } ?>
                        </ul>
                    </dd>
                <?php } ?>
            <?php } ?>
            <dd></dd>
        </dl>
    </div>
</div>
<!-- //end Main menu -->