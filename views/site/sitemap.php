<?php
/* @var $this app\components\View */
?>
<div class="subtitle">
    <div>
        <span><?= $this->h1 ?></span>
    </div>
</div>
<div class="divider-lg">
</div>
<div class="row content-row">
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <img class="img-responsive animate scale animated" src="<?= app\assets\AppAsset::path('images/sitemap-img.png') ?>">
    </div>
    <div class="divider-lg visible-xs">
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
        <?= app\components\IcmsHelper::renderSiteMap(null, ['class' => 'tree-list']) ?>
    </div>
</div>
