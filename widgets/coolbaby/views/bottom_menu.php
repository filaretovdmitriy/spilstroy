<?php

use app\components\IcmsHelper;
use app\models\Parameter;
use app\models\Tree;
?>
<footer>
    <div id="footer-collapsed">
        <div class="footer-navbar">
            <div class="container">
                <div class="arrow link hidden-xs hidden-sm">
                    <i class="icon flaticon-down14"></i>
                </div>
                <?php foreach ($pages as $key => $page) { ?>
                    <div class="collapsed-block">
                        <div class="inside">
                            <h3><span class="link"><?= $page->name_menu ?></span><a class="expander visible-sm visible-xs" href="#TabBlock-<?= $key ?>">+</a></h3>
                            <div class="tabBlock" id="TabBlock-<?= $key ?>">
                                <ul class="menu">
                                    <?php foreach ($page->getPages()->andWhere(['status' => Tree::STATUS_ACTIVE, 'in_menu' => 1])->orderBy(['sort' => SORT_ASC])->each() as $child) { ?>
                                        <li><a href="<?= $child->url ?>"><?= $child->name_menu ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 copyright">
                        <?= IcmsHelper::viewContent(Parameter::getValue(3)) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>