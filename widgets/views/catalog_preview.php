<?php

if (!empty($items)) {
    ?>
    <ul class="list-inline">
        <?php
        $cnt = 0;
        foreach ($items as $item) {
            ?>
            <li class="item">
                <?= $item->renderItemMini() ?>
            </li>
        <?php } ?>
    </ul>
<?php } ?>