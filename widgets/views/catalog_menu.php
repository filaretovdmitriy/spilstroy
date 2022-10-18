<?php

use app\components\IcmsHelper;
use app\models\Parameter;
use app\models\CatalogCategorie;
?>

<?php foreach ($categories as $categorie) { ?>
    <div class="nav-item <?if(empty($categorie->subcatalogs)) echo "no-submenu"?>">
        <a href="<?= yii\helpers\Url::to(['site/catalog_categorie', 'catalog_categorie_alias' => $categorie->alias]) ?>"><?= $categorie->name ?></a>
        
            <?
                if($categorie->subcatalogs)
                {
                    ?>
                    <div class="sub-menu">
                    <?
                    foreach($categorie->subcatalogs as $subcategorie){
                        ?>
                        <a href="<?= yii\helpers\Url::to(['site/catalog_categorie', 'catalog_categorie_alias' => $subcategorie->alias]) ?>"><?= $subcategorie->name ?></a>
                        <?
                    }
                    ?>
                    </div>
                    <?
                }
            ?>
            
    </div>
<?}?>