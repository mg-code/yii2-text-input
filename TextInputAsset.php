<?php
namespace mgcode\textInput;

use yii\web\AssetBundle;

class TextInputAsset extends AssetBundle
{
    public $sourcePath = '@mgcode/textInput/assets';
    public $js = [
        'mgcode.text-input.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}