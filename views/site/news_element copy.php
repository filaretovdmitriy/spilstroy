<?php
/* @var $this app\components\View */
?>
<h1><?= $content->name ?></h1>
<p class="full-news-date"><?= date('d.m.Y',strtotime($content->g_date)) ?></p>
<?= $content->content ?>
<p></p>
<a href="/news">Вернуться к списку новостей</a>