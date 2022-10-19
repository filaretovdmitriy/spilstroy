<?php
/* @var $this app\components\View */

use yii\widgets\LinkPager;
use app\components\IcmsHelper;
?>

    <?php if (!empty($contents)) { ?>
        <h1><?= $this->h1 ?></h1>
        <?= $this->tree->getContent() ?>

    
        <?php foreach ($contents as $content) { ?>
            <div class="news-element">
                
                    <?php if (!empty($content->image)) { ?>
                        <div class="image-wrapper">
                            <img src="/upload/icms/images/content/<?= $content->image ?>" alt="<?= $content->name ?>">
                        </div>
                    <?php } ?>
                
                <div class="news-content <?php if (empty($content->image)) echo 'fullwidth'; ?>">
                    <div class="news-date"><?= IcmsHelper::dateTimeFormat('d.m.Y', $content->g_date) ?> </div>
                    <a href="<?= yii\helpers\Url::to(['site/news_element', 'alias' => $content->alias]) ?>" class="news-title">​<?= $content->name ?></a>
                    <div class="news-anons"><?= $content->anons ?></div>
                </div>
            </div>
                        
                    
        <?php } ?>
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]);
    } else { ?>
        
            <strong>Список новостей пуст</strong>
       
    <?php } ?>
