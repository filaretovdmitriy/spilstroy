<?php

namespace app\models;

use yii\db\ActiveRecord;

class CatalogWishlist extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%catalog_wishlist}}';
    }

    public function rules()
    {
        return [
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['catalog_id', 'exist', 'targetClass' => Catalog::class, 'targetAttribute' => 'id'],
        ];
    }

}
