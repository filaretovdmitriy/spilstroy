<section class="container content brands-slider">
    <div class="subtitle right-space">
        <div>
            <span>Бренды и спонсоры</span>
        </div>
    </div>
    <div class="brands-carousel">
        <div class="slides">
            <?php foreach ($slides as $slide) { ?>
                <div>
                    <a href="<?= empty($slide->link) ? '#' : $slide->link ?>">
                        <img src="<?= $slide->getResizePath('image', 160, 65, 2) ?>" alt="<?= $slide->name ?>">
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>