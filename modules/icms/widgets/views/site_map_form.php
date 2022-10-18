<?php

use app\modules\icms\widgets\drop_down_list\DropDownList;
use app\modules\icms\widgets\CheckBox;
use yii\helpers\ArrayHelper;
?>
<table class="table width-100 table-striped">
    <thead>
        <tr>
            <th class='width-300'>Название модуля</th>
            <th>Добавлять в siteMap</th>
            <th>Частота изменения</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($models as $name => $model) {
            if (ArrayHelper::isAssociative($model)) {
                ?>
                <tr>
                    <td><?= $model['title'] ?></td>
                    <td>
                        <?=
                        CheckBox::widget([
                            'name' => 'sitemap-active[' . $name . ']',
                            'checked' => true
                        ])
                        ?>
                    </td>
                    <td><?=
                        DropDownList::widget([
                            'items' => $changefreq,
                            'name' => 'sitemap-change[' . $name . ']',
                            'selection' => 4
                        ])
                        ?>
                    </td>
                </tr>
                <?php
            } else {
                foreach ($model as $key => $values) {
                    ?>
                    <tr>
                        <td><?= $values['title'] ?></td>
                        <td>
                            <?=
                            CheckBox::widget([
                                'name' => 'sitemap-active[' . $name . '][' . $key . ']',
                                'checked' => true
                            ])
                            ?>
                        </td>
                        <td><?=
                            DropDownList::widget([
                                'items' => $changefreq,
                                'name' => 'sitemap-change[' . $name . '][' . $key . ']',
                                'selection' => 4
                            ])
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
    </tbody>
</table>