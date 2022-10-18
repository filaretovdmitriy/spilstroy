<?php

use yii\helpers\Url;
?>
<section class="container content">
    <div class="subtitle right-space">
        <div>
            <span>Популярные товары</span>
        </div>
    </div>
    <div class="slick-arrows">
        <button type="button" class="slick-prev" id="prevSlick" style="display: block;">Previous</button>
        <button type="button" class="slick-next" id="nextSlick" style="display: block;">Next</button>
    </div>
    <div class="products-nospace-outer row1">
        <div class="products-nospace">
            <div class="slides row1">
                <?php foreach ($goods as $key => $good) { ?>
                    <div class="carousel-item <?= $key ?>">
                        <div class="product-preview">
                            <a href="<?= Url::to(['site/catalog', 'catalog_categorie_alias' => $categorieAliases[$good->catalog_categorie_id], 'catalog_id' => $good->id, 'catalog_alias' => $good->alias]) ?>" class="preview-image">
                                <img class="img-default" src="<?= $good->getResizePath('image', 256, 273, 1) ?>" alt="">
                            </a>
                            <div class="hover">
                                <div class="inside">
                                    <h3 class="title"><a href="<?= Url::to(['site/catalog', 'catalog_categorie_alias' => $categorieAliases[$good->catalog_categorie_id], 'catalog_id' => $good->id, 'catalog_alias' => $good->alias]) ?>"><?= $good->name ?></a></h3>
                                    <span class="price new"><?= $good->price ?> р.</span>
                                    <?php if (!empty($good->price_old)) { ?>
                                        <span class="price old"><?= $good->price_old ?> р.</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- //end product view ajax container -->
</section>