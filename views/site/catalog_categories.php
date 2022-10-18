<?php
/* @var $this app\components\View */
?>

<h1><?= $this->h1 ?></h1>
<?= $this->tree->getContent() ?>

<div class="catalog-categories-main">
    <?php foreach ($categories as $categorie) { ?>
            <a class="catalog-categorie-element" href="<?= yii\helpers\Url::to(['site/catalog_categorie', 'catalog_categorie_alias' => $categorie->alias]) ?>">
                <div class="image-wrapper">
                    <img src="<?= $categorie->getPath('image') ?>" alt="">
                </div>
                <span><?= $categorie->name ?></span>
            </a>
    <?php } ?>
</div>