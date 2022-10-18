<?php
/* @var $this app\components\View */
use app\assets\AppAsset;
?>
<?php
                    
                    if(!empty($catalog))
                    {
                        ?>
                        <div class="catalog-list">
                            <h2>Самое популярное</h2>
                            <div class="catalog-items">
                                <?foreach($catalog as $item){
                                    $link = \yii\helpers\Url::to([
                                        'site/catalog_element',
                                        'catalog_categorie_alias' => $item->categorie->alias,
                                        'catalog_id' => $item->id,
                                        'catalog_alias' => $item->alias,
                                    ]);
                                    ?>
                                    <div class="catalog-item">
                                        <div class="catalog-shadow-item">
                                            <a class="image-wrapper" href="<?=$link?>" >
                                            <?php if (!empty($item->image)) { ?>
                                                <img class="img-responsive img-default" src="<?= $item->getResizePath('image', 170, 220) ?>" alt="<?= $item->name ?>">
                                            <?php } ?>
                                            </a>
                                            <a href="<?=$link?>" class="item-name"><?= $item->name?></a>
                                            <div class="item-price"><?= number_format($item->price, 2, '.', ' '); ?> руб</div>
                                            <a href="<?=$link?>" class="item-button add-to-cart">Купить</a>
                                        </div>
                                    </div>
                                <?}?>

                            </div>  
                            
                        </div>

                        <?
                    }
                    ?>
                    
                    <div class="main-text-wrapper main-page">
                        <div>
                            <img src="/upload/images/mainpage-circle.png" alt="">
                        </div>
                        <div>
                            <h1><?=$this->h1;?></h1>
                            <?=$this->tree->getContent();?>
                        </div>
                        
                    </div>

