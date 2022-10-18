<?php

namespace app\modules\icms\widgets\sortable;

use yii\web\AssetBundle;

class SortableAssets extends AssetBundle
{

    public $sourcePath = '@app/assets/sources/jquery.ui.sortable';
    public $js = [
        'jquery.ui.sortable-animation.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];

}
