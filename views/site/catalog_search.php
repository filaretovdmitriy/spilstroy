<?php
/* @var $this app\components\View */

use yii\widgets\LinkPager;
?>

<section class="col-sm-12 col-md-12 col-lg-12 content-center">
    <h1>Поиск по запросу: <?= $searchText ?></h1>
    <div class="products-list">
        
        <?php if (!empty($catalog)) { ?>
            <?php foreach ($catalog as $item) {
                $categorie = $item->categorie;
                ?>
                <div class="product-preview-outer">
                    <div class="product-preview">
                        <div class="preview-image-outer">
                            <a href="<?= \yii\helpers\Url::to([
                                'site/catalog_element',
                                'catalog_categorie_alias' => $categorie->alias,
                                'catalog_id' => $item->id,
                                'catalog_alias' => $item->alias,
                            ]) ?>" class="preview-image">
                                <?php if (!empty($item->image)) { ?>
                                    <img class="img-responsive img-default" src="<?= $item->getPath('image') ?>" alt="<?= $item->name ?>">
                                <?php } ?>
                            </a>
                        </div>
                        <h3 class="title">
                            <a href="<?= \yii\helpers\Url::to([
                                    'site/catalog_element',
                                    'catalog_categorie_alias' => $categorie->alias,
                                    'catalog_id' => $item->id,
                                    'catalog_alias' => $item->alias,
                                ]) ?>">
                                <?= $item->name ?>
                            </a>
                        </h3>
                        <span class="price new"><?= $item->price ?> р.</span>
                        <?php if (!empty($item->price_old)) { ?>
                            <span class="price old"><?= $item->price_old ?> р.</span>
                        <?php } ?>
                        <ul class="product-controls-list">
                            <li><a href="#"><span class="icon flaticon-heart68"></span></a></li>
                            <li><a href="#drop-shopcart" class='add-to-cart open-cart'><span class="icon flaticon-shopping66"></span></a></li>
                        </ul>
                        <div class="info">
                            <?= $item->content ?>
                        </div>
                        <ul class="product-controls-list-row">
                            <li><a href="#"><span class="icon flaticon-heart68"></span></a></li>
                            <li><a href="#drop-shopcart" class='add-to-cart open-cart'><span class="icon flaticon-shopping66"></span></a></li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-xs-12">
                <div class="alert alert-info fade in">
                    <strong>Список пуст.</strong> Не найдено товаров соответсвующих Вашему запросу
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- Filters -->
    <div class="filters-panel">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'options' => ['class' => 'paginator pull-right text-right']
                ]) ?>
            </div>
        </div>
    </div>
</section>
