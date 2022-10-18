<?php
use yii\widgets\Breadcrumbs;
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
                <aside>
                    <nav class="catalog-left-menu">
                        <?= app\widgets\CatalogMenu::widget() ?>
                    </nav>
                    <?= app\widgets\ContentPreview::widget(['content_categorie_id'=>1]) ?>
                    <?= app\widgets\ArticlesPreview::widget(['content_categorie_id'=>2]) ?>
                </aside>
                <main>
                <div class="inner-page-text">
                        <?= Breadcrumbs::widget(
                                [
                                    'links' => \Yii::$app->controller->bread,
                                    'activeItemTemplate' => '{link}',
                                    'options' => ['class' => 'breadcrumbs'],
                                    'itemTemplate' => '<li>{link}</li><span class="separator">-</span>',
                                    'tag' => 'ul'
                                ]
                            ) ?>
                        <div class="main-text-wrapper">
                            <?= $content ?>
                        </div>
                    </div>
                </main>
                
                

                   
    
<?php $this->endContent(); ?>