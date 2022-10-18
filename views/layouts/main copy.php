<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\coolbaby\BasketMini;
use yii\widgets\ActiveForm;
use app\forms\SearchForm;
use app\models\Parameter;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <!--[if IE]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <![endif]-->
        <meta id="viewport_meta" name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= Html::encode($this->title) ?></title>
        
        <?php $this->head() ?>
    </head>
    <body class="responsive">
        <?php $this->beginBody() ?>
        <div class="loader">
            <div class="fond">
                <div class="contener_general">
                    <div class="contener_mixte">
                        <div class="ballcolor ball_1">
                            &nbsp;
                        </div>
                    </div>
                    <div class="contener_mixte">
                        <div class="ballcolor ball_2">
                            &nbsp;
                        </div>
                    </div>
                    <div class="contener_mixte">
                        <div class="ballcolor ball_3">
                            &nbsp;
                        </div>
                    </div>
                    <div class="contener_mixte">
                        <div class="ballcolor ball_4">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?= app\widgets\coolbaby\CatalogMenu::widget(['offMode' => true]) ?>
        <div id="outer">
            <div id="outer-canvas">
                <!-- Navbar -->
                <header>
                    <!-- Search -->
                    <div id="openSearch">
                        <div class="container">
                            <div class="inside">
                                <?php
                                $model1 = new SearchForm();
                                $form = ActiveForm::begin([
                                            'method' => 'get',
                                            'action' => ['site/catalog_search'],
                                            'id' => 'searchHeader'
                                ]);
                                ?>
                                <div class="input-outer">
                                    <?= $form->field($model1, 'searchText')->textInput(['class' => 'search-input', 'placeholder' => 'Поиск...'])->label(false) ?>
                                </div>
                                <div class="button-outer">
                                    <button type="button" class="pull-right search-close"><i class="icon">&#10005;</i></button>
                                    <button type="submit" class="pull-right"><i class="icon icon-xl flaticon-zoom45"></i></button>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                    <!-- //end Search -->
                    <div id="newsLine">
                        <div class="container">
                            <div class="row">
                                <?= \app\widgets\coolbaby\NewsHeader::widget(['contentId' => 1]) ?>
                                <div class="col-xs-5 col-sm-3 col-md-2 top-link pull-right">
                                    <div class="btn-outer btn-search">
                                        <a href="#" class="btn btn-xs btn-default" data-toggle="dropdown"><span class="icon icon-lg flaticon-zoom45"></span></a>
                                    </div>
                                    <div class="btn-outer btn-shopping-cart">
                                        <?= BasketMini::widget() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Back to top -->
                    <div class="back-to-top">
                        <span class="arrow-up"><img src="<?= AppAsset::path('images/icon-scroll-arrow.png') ?>"></span><img src="<?= AppAsset::path('images/icon-scroll-mouse.png') ?>" alt="">
                    </div>
                    <!-- //end Back to top -->
                    <section class="navbar">
                        <div class="background">
                            <div class="container">
                                <div class="row">
                                    <div class="header-left col-sm-5 col-md-8">
                                        <div class="row">
                                            <div class="navbar-welcome col-md-6 compact-hidden hidden-sm hidden-xs">
                                                <?= Yii::$app->name ?>
                                            </div>
                                            <!-- Mobile menu Button-->
                                            <div class="col-xs-2 visible-xs">
                                                <div class="expand-nav compact-hidden">
                                                    <a href="#off-canvas-menu" id="off-canvas-menu-toggle"><span class="icon icon-xl flaticon-menu29"></span></a>
                                                </div>
                                            </div>
                                            <!-- //end Mobile menu Button -->
                                            <!-- Logo -->
                                            <div class="navbar-logo col-xs-10 col-sm-10 col-md-6 text-center">
                                                <a href="/">
                                                    <?= Html::img(app\components\IcmsHelper::getResizePath(Parameter::getValue(2, true), 240, 75, 4), ['id' => 'logo-header', 'alt' => Yii::$app->name]) ?>
                                                </a>
                                            </div>
                                            <!-- //end Logo -->
                                            <div class="clearfix visible-xs">
                                            </div>
                                            <!-- Secondary menu -->
                                            <div class="top-link pull-right compact-visible">
                                                <div class="btn-outer btn-shopping-cart">
                                                    <?= BasketMini::widget() ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="navbar-secondary-menu col-sm-7 col-md-4 compact-hidden">
                                        <?= \app\widgets\coolbaby\AccountDropDown::widget() ?>
                                    </div>
                                </div>
                            </div>
                            <?= \app\widgets\coolbaby\CatalogMenu::widget() ?>
                        </div>
                    </section>
                    <div class="navbar-height">
                    </div>
                </header>
                <?= $content ?>
                <?= app\widgets\coolbaby\BottomMenu::widget() ?>
            </div>
        </div>
        <?= app\widgets\back_call_popup\BackCallPopup::widget() ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>