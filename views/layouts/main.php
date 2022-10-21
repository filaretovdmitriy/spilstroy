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
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
<script type="text/javascript" src="https://cdn.envybox.io/widget/cbk.js?wcb_code=46732b745be9ee6042898f0ba47f1d38" charset="UTF-8" async></script>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="site-wrapper">
            <header>
                <div class="header-wrapper page-wrapper">
                    <div class="logo-wrapper">
                        <div class="logo"><a href="/"><img src="<?= AppAsset::path('images/logo.svg') ?>" name="" /></a></div>
                    </div>
                    
                    <div class="email">
                        <div class="icon-wrapper"><img src="<?= AppAsset::path('images/header-email-icon.svg') ?>"></div>
                        <a href="mailto:info@spilstroy.ru">info@spilstroy.ru</a>
                    </div>
                    <div class="phones">
                        <div class="icon-wrapper"><img src="<?= AppAsset::path('images/header-phone-icon.svg') ?>"></div>
                        <div class="phones-list">
                            <?
                                if(is_array(Parameter::getValue(3))) {
                                    foreach(Parameter::getValue(3) as $phone)
                                    {
                                        ?>
                                            <a href="tel:+<?=preg_replace('/[^0-9]/', '', $phone)?>"><?=$phone?></a>
                                        <?
                                    }
                                }
                            ?>
                            
                            
                        </div>
                    </div>

                    <div class="phones">
                        <div class="icon-wrapper"><img src="<?= AppAsset::path('images/header-phone-icon.svg') ?>"></div>
                        <div class="phones-list">
                            
                            <a href="tel:+<?=preg_replace('/[^0-9]/', '', Parameter::getValue(4))?>"><?=Parameter::getValue(4)?></a>
                            <span>Производство</span>
                       </div>
                    </div>

                    <div class="phones-mobile">
                            <?
                                if(is_array(Parameter::getValue(3))) {
                                    foreach(Parameter::getValue(3) as $phone)
                                    {
                                        ?>
                                            <a href="tel:+<?=preg_replace('/[^0-9]/', '', $phone)?>"><?=$phone?></a>
                                        <?
                                    }
                                }
                            ?>
                        <a href="tel:+<?=preg_replace('/[^0-9]/', '', Parameter::getValue(4))?>"><?=Parameter::getValue(4)?>
                            <span>Производство</span>        
                        </a> 
                    </div>

                    <?= app\widgets\BasketMini::widget() ?>
                    
                </div>
            </header>

            <div class="menu-wrapper page-wrapper">
                <div class="mobile-toogle-nav">
                    <div class="mobile-toogle-nav-title">Меню</div>
                    <div class="mobile-toogle-nav-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <nav>
                    <div class="catalog-toggle"><a class="catalog-toggle-link" href="#">Каталог</a></div>
                    <?= app\widgets\TopMenu::widget() ?>
                </nav>
            </div>
            
            <section class="main-wrapper page-wrapper">
                
                    <?= $content ?>
                
            </section>
            <footer>
                <div class="footer-wrapper page-wrapper">
                    <div class="logo-wrapper"><a href="/"><img src="<?= AppAsset::path('images/logo.svg') ?>" name="" /></a></div>
                    <nav class="footer-menu">
                    <?= app\widgets\FooterMenu::widget() ?>
                    </nav>
                    <div class="phones">
                        <img src="<?= AppAsset::path('images/phones-icon.svg') ?>">
                        <div class="phones-list">
                            <?
                                if(is_array(Parameter::getValue(3))) {
                                    foreach(Parameter::getValue(3) as $phone)
                                    {
                                        ?>
                                            <a href="tel:+<?=preg_replace('/[^0-9]/', '', $phone)?>"><?=$phone?></a>
                                        <?
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
 <?/*
<script>
	  (function(w, d, u, i, o, s, p) {
		  if (d.getElementById(i)) { return; } w['MangoObject'] = o;
		  w[o] = w[o] || function() { (w[o].q = w[o].q || []).push(arguments) }; w[o].u = u; w[o].t = 1 * new Date();
		  s = d.createElement('script'); s.async = 1; s.id = i; s.src = u; s.charset = 'utf-8';
		  p = d.getElementsByTagName('script')[0]; p.parentNode.insertBefore(s, p);
	  }(window, document, '//widgets.mango-office.ru/widgets/mango.js', 'mango-js', 'mgo'));
	  mgo({multichannel: {id: 12481}});
  </script>
*/?>    

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(53307058, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/53307058" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>