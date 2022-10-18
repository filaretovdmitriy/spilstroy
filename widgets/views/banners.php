<?php if (!empty($items)) { ?>
    <div class="row margin-bottom-60">
        <?php
        $cnt = 0;
        foreach ($items as $item) {
            ?>
            <div class="col-md-6 md-margin-bottom-30">
                <div class="overflow-h">
                    <div>
                        <?php if (!empty($item->file)) { ?>
                            <img src="<?= $item->getPath('file') ?>" alt="<?= $item->name ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>