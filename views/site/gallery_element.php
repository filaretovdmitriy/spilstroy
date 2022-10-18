<?php
/* @var $this app\components\View */
?>
<div class="container">
    <div class="subtitle">
        <div>
            <span><?= $this->h1 ?></span>
        </div>
    </div>
</div>
<div class="images-gallery three-columns">
    <div class="images-nospace">
        <?php if (!empty($gallery)) {
            foreach ($gallery as $img) { ?>
                <div class="image-thumbnail">
                    <a href="<?= $img->getPath('image') ?>" class="gallery-group">
                        <?php if (!empty($img->image)) { ?>
                            <img src="<?= $img->getPath('image') ?>" alt="<?= $img->name ?>">
                        <?php } else { ?>
                            <img src="<?= ShopCoolBabyAsset::path('images/gallery/gallery-thumbnail-three-01.jpg') ?>">
                        <?php } ?>
                        <div class="hover"></div>
                    </a>
                </div>
            <?php
            }
        } else { ?>
            <div class="alert alert-info fade in">
                <strong>Список пуст</strong>
            </div>
        <?php } ?>
    </div>
</div>