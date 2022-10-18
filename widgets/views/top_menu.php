<?php

use app\components\IcmsHelper;
use app\models\Parameter;
use app\models\Tree;
?>

<?php foreach ($pages as $key => $page) { ?>
    <a class="nav-item" href="<?= $page->url ?>"><?= $page->name_menu ?></a>
<?}?>