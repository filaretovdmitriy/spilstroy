<?php
/* @var $this app\components\View */

use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\back_call_popup\BackCallPopup;
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
            <?/*<div class="catalog-rate">
                <div class="catalog-stars"></div>
                <div class="catalog-produce"><img src=""></div>
            </div>*/?>

            <div class="price-variant">
                Отгрузка с пилорамы (80 км от МКАД, от 30 куб. м) - <?= number_format($catalog->price, 2, '.', ' '); ?> руб/куб<br>
                Отгрузка от Московского склада - <?= number_format($catalog->moscowprice, 2, '.', ' '); ?> руб/куб
            </div>

            <div class="catalog-element-price">
                <span class="catalog-element-price-value"><?= number_format($catalog->price, 2, '.', ' '); ?></span> руб/куб. м
            </div>

            <div class="in-stock"><img src="<?= AppAsset::path('images/check-icon.png') ?>" />Есть в наличии</div>

            <div class="catalog-keep-tab">
                <div class="catalog-keep-title">Склад</div>
                <div class="catalog-keep-tab-select">
                    <div class="catalog-keep-tab-item active" data-type="warehouse" data-price="<?= number_format($catalog->price, 2, '.', ' '); ?>">Производство</div>
                    <div class="catalog-keep-tab-item" data-type="moscow" data-price="<?= number_format($catalog->moscowprice, 2, '.', ' '); ?>">Москва</div>
                </div>
                
            </div>
            
           
            <div class="catalog-buy-buttons">
                <div class="buy-count-buttons">
                    <a class="button-change-count button-minus-item" href="#">-</a>
                    <input type='text' data-catalog-id="<?= $catalog->id ?>" class="form-control input-quantity quantity-field" value="1" id='quantity-field-<?= $catalog->id ?>' />
                    <a class="button-change-count button-plus-item" href="#">+</a>
                </div>
                <div class="btn but-button basket-add-item"  data-id="<?= $catalog->id ?>"><span>В корзину</span></div>
                <a class="btn-whatsapp" href="https://wa.me/message/PKWQXBSOLBUZH1"><img src="<?= AppAsset::path('images/whatsapp-icon.svg') ?>"><span>Связаться с нами</span></a>
            </div>
            <div class="catalog-callback-button catalog-buy-buttons">
                <?= BackCallPopup::widget(['good' => $catalog]) ?>
            </div>



            <div class="catalog-alter-variants">
                <div class="catalog-alter-variant-title">Альтернативные варианты</div>
                <?
                    if(!empty($catalogAlternate)){ 
                        
                    foreach($catalogAlternate as $item){
                        $link = \yii\helpers\Url::to([
                            'site/catalog_element',
                            'catalog_categorie_alias' => $item->categorie->alias,
                            'catalog_id' => $item->id,
                            'catalog_alias' => $item->alias,
                        ]);
                        ?>
                            <a class="catalog-alter-variant-item" href="<?=$link?>">
                            <?php if (!empty($item->image)) { ?>
                                <img src="<?= $item->getResizePath('image', 400, 400) ?>" alt="<?= $item->name ?>">
                            <?php } ?>    
                                <div class="catalog-alter-variant-item-name"><?=$item->name?></div>
                                <div class="catalog-alter-variant-item-price"><?= number_format($catalog->price, 2, '.', ' '); ?> руб/м<sup>3</sup></div>
                            </a>
                        <?
                    }
        
                    }
                       
                ?>
            </div>
        </div>
    </div>
</div>

<div class="catalog-description-content-tabs">
    <?= $catalog->content ?>
</div>

<?php
if(!empty($catalogRelated)){
    ?>
    <div class="catalog-list">
        <h2>Смотрите также</h2>
        <div class="catalog-items square">
    <?
    foreach($catalogRelated as $item){
        $link = \yii\helpers\Url::to([
            'site/catalog_element',
            'catalog_categorie_alias' => $item->categorie->alias,
            'catalog_id' => $item->id,
            'catalog_alias' => $item->alias,
        ]);
        ?>
        <div class="catalog-item">
                <div class="catalog-shadow-item">
                    <a  href="<?=$link?>" class="image-wrapper">
                    <?php if (!empty($item->image)) { ?>
                        <img class="img-responsive img-default" src="<?= $item->getResizePath('image', 170, 220) ?>" alt="<?= $item->name ?>">
                    <?php } ?>
                    </a>
                    <a  href="<?=$link?>" class="item-name"><?= $item->name?></a>
                    <div class="item-price"><?= number_format($item->price, 2, '.', ' '); ?> руб</div>
                    <a href="<?=$link?>" class="item-button add-to-cart">Купить</a>
                </div>
            </div>
        <?
    }?>
        </div>
    </div>
<?    
}
?>
                   
                    
