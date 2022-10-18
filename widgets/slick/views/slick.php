<?php
if (count($items) > 0) {
    ?>
    <div class="slick" id="slick_<?= $id ?>">
        <?php foreach ($items as $item) { ?>
            <div><img src="/upload/icms/images/slides/<?= $item->image ?>"/></div>
        <?php } ?>
    </div>
    <?php
}