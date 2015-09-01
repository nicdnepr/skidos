<?php

namespace app\assets;

use yii\web\AssetBundle;

class AddThisAsset extends AssetBundle
{
    public $js = [
        '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5582f5a74086765f'
    ];
    
    public $jsOptions = [
        'async' => 'async'
    ];
}
