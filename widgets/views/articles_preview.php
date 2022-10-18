<?php
/* @var $this app\components\View */

use app\components\IcmsHelper;
?>
<?php if (!empty($items)) { ?>
    <div class="news-preview">
        <div class="news-preview-title">
            <p>Статьи</p>
            <a href="/articles">Все статьи</a>
        </div>
            <?php
            $cnt = 0;
            foreach ($items as $item) {
                ?>
                    <a href="/articles/<?= $item->alias ?>" class="news-preview-item">
                        <?/*php if (!empty($item->image)) { ?>
                            <img src="/upload/icms/images/content/<?= $item->image ?>" alt="<?= $item->name ?>">
                        <?php } */?>
                        <div class="news-date"><?= IcmsHelper::dateTimeFormat('d.m.Y', $item->g_date) ?></div>
                        <div><?= $item->name ?></div>
                    </a>
    <?php } ?>
    </div>
<?php } ?>