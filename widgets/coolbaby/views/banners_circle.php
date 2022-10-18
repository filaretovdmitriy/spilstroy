<section class="container content circle_banners slick-style2">
    <div class="row">
        <?php foreach ($items as $item) { ?>
            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                <div class="banner-circle animate fadeInDown animated">
                    <?php if (empty($item->link)) { ?>
                        <div class="image">
                            <img src="<?= $item->getResizePath('file', 370, 370) ?>" alt="<?= $item->name ?>" class="animate-scale">
                        </div>
                        <div class="title">
                            <span><?= $item->name ?></span>
                        </div>
                    <?php } else { ?>
                        <a href="<?= $item->link ?>">
                            <div class="image">
                                <img src="<?= $item->getResizePath('file', 370, 370) ?>" alt="<?= $item->name ?>" class="animate-scale">
                            </div>
                            <div class="title">
                                <span><?= $item->name ?></span>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</section>