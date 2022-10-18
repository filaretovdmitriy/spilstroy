<?php
/* @var $this app\components\View */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use app\assets\AppAsset;
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
        <?php if (!empty($gallerys)) {
            foreach ($gallerys as $gallery) { ?>
                <div class="image-thumbnail">
                    <a href="<?= Url::to(['site/gallery_element', 'gallery_categorie_id' => $gallery->id]) ?>">
                        <?php if (!empty($gallery->image)) { ?>
                            <img src="<?= $gallery->getPath('image') ?>" alt="<?= $gallery->name ?>">
                        <?php } else { ?>
                            <img src="<?= AppAsset::path('images/gallery/gallery-thumbnail-three-01.jpg') ?>">
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
<div class="container">
    <div class="text-center">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'options' => ['class' => 'paginator']
        ]) ?>
    </div>
</div>