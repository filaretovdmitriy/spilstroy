<?php
use app\assets\AppAsset;
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>

                <aside>
                    <nav class="catalog-left-menu">
                        <?= app\widgets\CatalogMenu::widget() ?>
                    </nav>
                    
                    <?= app\widgets\ContentPreview::widget(['content_categorie_id'=>1]) ?>
                    <?= app\widgets\ArticlesPreview::widget(['content_categorie_id'=>2]) ?>
                </aside>
                <main>
                <div class="slider-wrapper">
                    <?= app\widgets\coolbaby\SliderMain::widget(['sliderId' => 1]) ?>
                </div>
                    <?= app\widgets\Features::widget() ?>

                    <div class="mainpage-banners">
                        <a href="/catalog/doska_strogannaya/">
                            <span>Доска строганная</span>
                            <img src="<?= AppAsset::path('images/banners/01.jpg') ?>">
                        </a>
                        <a href="/catalog/doska_obreznaya_pervyy_sort/">
                            <span>Доска обрезная</span>
                            <img src="<?= AppAsset::path('images/banners/02.jpg') ?>">
                        </a>
                        <a href="/catalog/brus_obreznoi/">
                            <span>Брус обрезной</span>
                            <img src="<?= AppAsset::path('images/banners/03.jpg') ?>">
                        </a>
                        <a href="/catalog/brus_strogannyy/">
                            <span>Брус строганный</span>
                            <img src="<?= AppAsset::path('images/banners/04.jpg') ?>">
                        </a>
                    </div>

                    <?=$content?>
                </main>





<?/*= \app\widgets\coolbaby\BannersCircle::widget(['groupId' => 1]) ?>

<?= app\widgets\coolbaby\CatalogPopular::widget() ?>

<?= app\widgets\coolbaby\Blog::widget() ?>

<?= app\widgets\coolbaby\Brands::widget() */?>

<?php $this->endContent(); ?>

