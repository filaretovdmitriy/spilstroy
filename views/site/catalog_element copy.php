<?php
/* @var $this app\components\View */

use yii\widgets\Breadcrumbs;
?>

<div class="catalog-element-item">
    <h1><?= $catalog->name ?></h1>
    <div class="catalog-element-info">
        <div class="slider-image-wrapper">
            <div class="main-image">
                    <?php if (!empty($catalog->image)) { ?>
                        <a href="<?= $catalog->getPath('image') ?>"><img src="<?= $catalog->getResizePath('image', 400, 400) ?>" alt="<?= $catalog->name ?>"></a>
                    <?php } ?>
            </div>
            <?php foreach ($catalog->images as $pic) { ?>
                <div class="carousel-images">
                    <a><img src="<?= $pic->getResizePath('image', 50, 50) ?>" alt="<?= $pic->name ?>"/></a>
                </div>
            <?php } ?>
        </div>
        <div class="catalog-element-description">
            <div class="catalog-rate">
                <div class="catalog-stars"></div>
                <div class="catalog-produce"><img src=""></div>
            </div>
            <div class="catalog-description-content">
                <?= $catalog->content ?>
            </div>
            <div class="catalog-element-price">
                <?= $catalog->price ?>
            </div>
            <div class="catalog-buy-buttons">
                <div class="buy-count-buttons">
                    <div>-</div>
                    <div>
                        <input type='text' data-catalog-id="<?= $catalog->id ?>" class="form-control input-quantity quantity-field" value="1" id='quantity-field-<?= $catalog->id ?>' />
                    </div>
                    <div>+</div>
                </div>
                <div class="btn but-button basket-add-item"  data-id="<?= $catalog->id ?>">В корзину</div>
            </div>
        </div>
    </div>
</div>

                    <div class="catalog-list">
                        <h2>Смотрите также</h2>
                        <div class="catalog-items">
                            <div class="catalog-item">
                                <div class="catalog-shadow-item">
                                    <a class="image-wrapper">
                                        <img src="images/catalog-element.jpg">
                                    </a>
                                    <a class="item-name">Название товара</a>
                                    <div class="item-price">12 000 руб</div>
                                    <div class="item-button add-to-cart">В корзину</div>
                                </div>
                            </div>
                            <div class="catalog-item">
                                <div class="catalog-shadow-item">
                                    <a class="image-wrapper">
                                        <img src="images/catalog-element.jpg">
                                    </a>
                                    <a class="item-name">Название товара</a>
                                    <div class="item-price">12 000 руб</div>
                                    <div class="item-button add-to-cart">В корзину</div>
                                </div>
                            </div><div class="catalog-item">
                                <div class="catalog-shadow-item">
                                    <a class="image-wrapper">
                                        <img src="images/catalog-element.jpg">
                                    </a>
                                    <a class="item-name">Название товара</a>
                                    <div class="item-price">12 000 руб</div>
                                    <div class="item-button add-to-cart">В корзину</div>
                                </div>
                            </div><div class="catalog-item">
                                <div class="catalog-shadow-item">
                                    <a class="image-wrapper">
                                        <img src="images/catalog-element.jpg">
                                    </a>
                                    <a class="item-name">Название товара товара товара товара</a>
                                    <div class="item-price">12 000 руб</div>
                                    <div class="item-button add-to-cart">В корзину</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
<!-- Product view -->
<div class="container">
    <div class="product-view row">
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="single-product">
                <?php if (!empty($catalog->image)) { ?>
                    <div class="carousel-item">
                        <img class="cloudzoom" src="<?= $catalog->getResizePath('image', 390, 525) ?>" data-cloudzoom="zoomImage: '<?= $catalog->getPath('image') ?>', autoInside : 991, zoomSizeMode: 'image'" alt="<?= $catalog->name ?>"/>
                    </div>
                <?php } ?>
                <?php foreach ($catalog->images as $pic) { ?>
                <div class="carousel-item">
                    <img class="cloudzoom" src="<?= $pic->getResizePath('image', 390, 525) ?>" data-cloudzoom="zoomImage: '<?= $pic->getPath('image') ?>', autoInside : 991, zoomSizeMode: 'image'" alt="<?= $pic->name ?>"/>
                </div>
                <?php } ?>
            </div>
            <div class="wrapper">
                <div class="slider-nav-simple">
                    <?php if (!empty($catalog->image)) { ?>
                        <div class="carousel-item">
                            <img src="<?= $catalog->getPath('image') ?>" alt="<?= $catalog->name ?>">
                        </div>
                    <?php } ?>
                    <?php foreach ($catalog->images as $pic) { ?>
                        <div class="carousel-item">
                            <img src="<?= $pic->getPath('image') ?>" alt="<?= $pic->name ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="clearfix visible-xs">
        </div>
        <div class="col-sm-6 col-md-6 col-lg-8">
            
            
            <?php if (!empty($catalog->old_price)) { ?>
                <span class="price old"><?= $catalog->old_price ?> р.</span>
            <?php } ?>
            <span class="price new"><?= $catalog->price ?> р.</span>
            <?php if (!empty($catalog->article)) { ?>
                <p>
                    <span><strong>Артикул: </strong></span> <span><?= $catalog->article ?></span>
                </p>
            <?php } ?>
            <div class="divider divider-sm">
            </div>
            <div class="line-divider">
            </div>
            <div class="divider divider-md">
            </div>
            <?php if ($catalogHasSku) { ?>
                <?php foreach ($skuGrid as $key => $value) {
                    ?>
                    <h3 class="shop-product-title"><?= $propsListId[$key] ?></h3>
                    <div class="product-options">
                        <?php foreach ($value as $key2 => $elem) { ?>
                            <input data-catalog-id="<?= $catalog->id ?>" class="get-sku" hidden type="radio" data-prop="<?= $key ?>" value="<?= $key2 ?>" id="prop-<?= $key2 ?>" name="prop-<?= $key ?>">
                            <label for="prop-<?= $key2 ?>" class="icon icon-size">
                                <?= $elem ?>
                            </label>
                        <?php } ?>
                    </div>
                    <?php
                }
            }
            ?>
                    
            <div class="form-inputs">
                <label>Количество:</label>
                <input type='text' data-catalog-id="<?= $catalog->id ?>" class="form-control input-quantity quantity-field" value="1" id='quantity-field-<?= $catalog->id ?>' />
                <?php if ($catalogHasSku) { ?>
                    <input type="hidden" id="product-sku-id-<?= $catalog->id ?>" value=""/>
                <?php } ?>
                <button data-id="<?= $catalog->id ?>" class="btn btn-cool btn-lg basket-add-item" type="submit"><i class="icon flaticon-shopping66"></i>В корзину</button>
            </div>
            <div class="divider divider-md">
            </div>
        </div>
    </div>
    <!-- //end Product view -->
    <!-- Tabs -->
    <section class="producttab">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1">Описание</a></li>
            <li><a data-toggle="tab" href="#tab-2">Отзывы (<?= count($catalog->feedbacksPublic) ?>)</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade active in">
                <?= $catalog->content ?>

                <h3 class="heading-md margin-bottom-20">Свойства</h3>
                <?php foreach ($catalog->props as $prop_alias => $prop) { ?>
                    <?= $propsList[$prop_alias] ?>:
                    <span>
                    <?php
                    if (!is_array($prop)) {
                        echo $prop;
                    } else {
                        echo implode(", ", $prop);
                    }
                    ?></span><br>
                <?php } ?>
            </div>
            <div id="tab-2" class="tab-pane fade">
                <?php foreach ($catalog->feedbacksPublic as $feed) { ?>
                    <div class="comments">
                        <div class="comment">
                            <div class="inside">
                                <p>
                                    <strong><?= $feed->user->name ?></strong><br>
                                    <?= \app\components\IcmsHelper::dateTimeFormat('Q d, Y H:i', $feed->g_date) ?>
                                </p>
                                <p><?= $feed->content ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="divider divider-sm"></div>
                <div class="line-divider"></div>
                <div class="divider divider-sm"></div>
                <div class="row">
                    <div class="col-sm-12">
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <h3 class="heading-md margin-bottom-30">Добавить отзыв о товаре</h3>
                        <form method="post" class="catalog-feedback-form sky-form sky-changes-4" id="sky-form3">
                            <fieldset>
                                <div class="margin-bottom-30">
                                    <textarea placeholder="Отзыв" class="form-control" name="message" cols="1" rows="6" id="catalog-feedback-form-message"></textarea>
                                </div>
                            </fieldset>
                            <footer class="review-submit">
                                <button class="button btn-cool sendCatalogFeedback" type="submit"><span class="icon flaticon-star129"></span>Отправить</button>
                            </footer>
                        </form>
                    <?php } else { ?>
                        <p>Только авторизованные пользователи могут оставлять отзывы о товарах</p>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<?php if ($catalog->getRelatedGoods()->exists()) { ?>
    <section class="container content slider-products">
        <div class="dotted-line right-space">
        </div>
        <!-- Products list -->
        <div class="pull-left vertical_title_outer right-space">
            <div>
                <span>Сопутствующие товары</span>
            </div>
        </div>
        <div class="pull-left carousel_outer">
            <div class="product-carousel">
                <?php foreach ($catalog->getRelatedGoods()->andWhere(['status' => $catalog::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->each() as $relatedGood) {
                    $categorie = $relatedGood->categorie;
                    ?>
                    <div class="carousel-item">
                        <div class="product-preview">
                            <div class="preview-image-outer">
                                <a href="<?= \yii\helpers\Url::to([
                                    'site/catalog',
                                    'catalog_categorie_alias' => $categorie->alias,
                                    'catalog_id' => $relatedGood->id,
                                    'catalog_alias' => $relatedGood->alias,
                                ]) ?>" class="preview-image">
                                    <?php if (!empty($relatedGood->image)) { ?>
                                        <img class="img-responsive img-default" src="<?= $relatedGood->getPath('image') ?>" alt="<?= $relatedGood->name ?>">
                                    <?php } ?>
                                </a>
                            </div>
                            <h3 class="title"><a href="<?= \yii\helpers\Url::to([
                                    'site/catalog',
                                    'catalog_categorie_alias' => $categorie->alias,
                                    'catalog_id' => $relatedGood->id,
                                    'catalog_alias' => $relatedGood->alias,
                                ]) ?>"><?= $relatedGood->name ?></a></h3>
                            <span class="price new"><?= $relatedGood->price ?> р.</span>
                            <?php if (!empty($relatedGood->price_old)) { ?>
                                <span class="price old"><?= $relatedGood->price_old ?> р.</span>
                            <?php } ?>
                            <ul class="product-controls-list">
                                <li><a href="#" class="wishlist-add-good" data-id="<?= $relatedGood->id ?>"><span class="icon flaticon-heart68"></span></a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- product view ajax container -->
            <div class="product-view-ajax-container">
            </div>
            <!-- //end product view ajax container -->
            <!-- Product view compact -->
            <div class="product-view-ajax">
                <div class="layar">
                </div>
                <div class="product-view-container">
                </div>
            </div>
            <!-- //end Product view compact -->
        </div>
        <!-- //end Products list -->
        <div class="clearfix">
        </div>
    </section>
<?php } ?>