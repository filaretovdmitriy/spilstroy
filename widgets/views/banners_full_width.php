<?php if (!empty($items)) { ?>
    <?php
    $cnt = 0;
    foreach ($items as $item) {
        if (!empty($item->file)) {
            ?>
            <img src="<?= $item->getPath('file') ?>" alt="<?= $item->name ?>">
        <?php
        }
    }
    ?>
<?php } ?>