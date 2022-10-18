<?php
/* @var $this app\components\View */

use yii\helpers\Html;
use app\components\IcmsHelper;

?>
<div class="subtitle">
    <div>
        <span><?= $this->h1 ?></span>
    </div>
</div>
<div>
    <?= $this->tree->getContent() ?>
</div>
<div id="wishlist-wraper">
<?php if (count($goods) > 0) { ?>
    <section class="shopping-cart">
        <div class="table-responsive">
            <table class="table table-striped" id="wishlist-table">
                <thead>
                    <tr>
                        <th colspan="2">Товар</th>
                        <th>Цена</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($goods as $good) { ?>
                        <tr id="wishlist-good-<?= $good->id ?>">
                            <td class="product-in-table image-table">
                                <?php if (!empty($good->image)) {
                                    echo Html::a(
                                        Html::img(IcmsHelper::getResizePath($good->getPath('image'), 120, 120), ['class' => 'img-responsive', 'alt' => $good->name]),
                                        yii\helpers\Url::to(['site/catalog', 'catalog_categorie_alias' => $good->categorie->alias, 'catalog_id' => $good->id, 'catalog_alias' => $good->alias])
                                    );
                                } ?>
                            </td>
                            <td class="product-in-table">
                                <h3><a href="<?= yii\helpers\Url::to(['site/catalog', 'catalog_categorie_alias' => $good->categorie->alias, 'catalog_id' => $good->id, 'catalog_alias' => $good->alias]) ?>"><?= $good->name ?></a></h3>
                                <?php if (!empty($good->article)) { ?>
                                    <span><?= $good->article ?></span>
                                <?php } ?>
                            </td>
                            <td><?= number_format($good->price, 2, '.', ' ') ?></td>
                            <td>
                                <button type="button" class="close wishlist-delete-good" data-id="<?= $good->id ?>"><span>&times;</span><span class="sr-only">Удалить</span></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
</div>
<div class="alert alert-info fade in" style="<?= count($goods) > 0?'display: none':'' ?>" id="wishlist-empty-message">
    <strong>Ваш список желаний пуст!</strong> Добавьте один или несколько товаров из <a href="<?= yii\helpers\Url::to(['site/catalog']) ?>">каталога</a>.
</div>