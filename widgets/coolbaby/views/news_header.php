<div class="col-sm-3 col-md-2 hidden-xs">
    <div class="title upper">
        <i class="icon flaticon-news"></i>Новости
    </div>
</div>
<div class="col-xs-5 col-sm-6 col-md-7 col-xs-push-1 col-sm-push-0">
    <div id="newsCarousel" class="slick-style1">
        <?php foreach ($items as $item) { ?>
            <div class="item upper">
                <div class="marquee">
                    <span class="date"><?= date('d.m.Y', strtotime($item->g_date)) ?>.</span> <?= $item->name ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>