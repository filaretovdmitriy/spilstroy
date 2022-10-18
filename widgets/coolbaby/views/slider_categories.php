<div class="category-slider slick-style5">
    <?php foreach ($items as $item) { ?>
        <div class="carousel-item">
            <img src="<?= $item->getPath('image') ?>" alt="<?= $item->name ?>">
        </div>
    <?php } ?>
</div>