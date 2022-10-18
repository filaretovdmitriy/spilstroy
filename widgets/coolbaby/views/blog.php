<?php

use app\components\IcmsHelper;
use yii\helpers\Url;
?>
<section class="blog-widget">
    <div class="container content">
        <div class="posts">
            <div class="subtitle">
                <div>
                    <span>Блог</span>
                </div>
            </div>
            <div class="slides slick-style3">
                <?php foreach ($items as $item) { ?>
                    <div class="carousel-item">
                        <a href="<?= Url::toRoute(['site/news_element', 'alias' => $item->alias]) ?>">
                            <?php if (!empty($item->image)) { ?>
                                <img src="<?= $item->getResizePath('image', 205, 195) ?>" alt="<?= $item->name ?>">
                            <?php } ?>
                            <span class="info"><?= $item->name ?> <span class="date"><?= IcmsHelper::dateTimeFormat('Q d, Y', $item->g_date) ?></span></span>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>