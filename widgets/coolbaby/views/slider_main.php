<?php if (count($items) > 0) { ?>
    <section class="slick" >
        <div class="tp-banner-container hidden-xs" id="slick_main_<?= $id ?>">
            <?php foreach ($items as $item) { ?>
                <div>
                    <img src="<?= $item->getPath('image') ?>"/>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>