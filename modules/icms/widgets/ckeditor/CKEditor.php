<?php

namespace app\modules\icms\widgets\ckeditor;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class CKEditor extends InputWidget
{

    public $editorOptions = [
        'preset' => 'icms',
        'extraAllowedContent' => '*{*}',
        'allowedContent' => true
    ];
    public $containerOptions = [];
    // Какой из файловых менеджеров подключать
    public $useCKFinder = false;
    public $useCKFSYS = true;
    private $_inline = false;

    public function init()
    {
        parent::init();

        if (array_key_exists('inline', $this->editorOptions)) {
            $this->_inline = $this->editorOptions['inline'];
            unset($this->editorOptions['inline']);
        }

        if (array_key_exists('preset', $this->editorOptions)) {
            if ($this->editorOptions['preset'] == 'basic') {
                $this->presetBasic();
            } elseif ($this->editorOptions['preset'] == 'standard') {
                $this->presetStandard();
            } elseif ($this->editorOptions['preset'] == 'full') {
                $this->presetFull();
            } elseif ($this->editorOptions['preset'] == 'icms') {
                $this->presetIcms();
            }
            unset($this->editorOptions['preset']);
        }

        if ($this->_inline && !isset($this->editorOptions['height']))
            $this->editorOptions['height'] = 100;

        if ($this->_inline && !isset($this->containerOptions['id']))
            $this->containerOptions['id'] = $this->id . '_inline';
    }

    private function presetBasic()
    {
        $options['height'] = 100;

        $options['toolbarGroups'] = [
            ['name' => 'undo'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'colors'],
            ['name' => 'links', 'groups' => ['links', 'insert']],
            ['name' => 'others', 'groups' => ['others', 'about']],
        ];
        $options['removeButtons'] = 'Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe';
        $options['removePlugins'] = 'elementspath';
        $options['resize_enabled'] = false;


        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }

    private function presetStandard()
    {
        $options['height'] = 300;

        $options['toolbarGroups'] = [
            ['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
            ['name' => 'editing', 'groups' => ['tools', 'about']],
            '/',
            ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
            ['name' => 'insert'],
            '/',
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'colors'],
            ['name' => 'links'],
            ['name' => 'others'],
        ];

        $options['removeButtons'] = 'Smiley,Iframe';

        if ($this->_inline) {
            $options['extraPlugins'] = 'sourcedialog';
            $options['removePlugins'] = 'sourcearea';
        }

        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }

    private function presetFull()
    {
        $options['height'] = 400;
        $options['toolbarGroups'] = [
            ['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
            ['name' => 'editing', 'groups' => ['find', 'spellchecker', 'tools', 'about']],
            '/',
            ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
            ['name' => 'forms'],
            '/',
            ['name' => 'styles'],
            ['name' => 'blocks'],
            '/',
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors', 'cleanup']],
            ['name' => 'links', 'groups' => ['links', 'insert']],
            ['name' => 'others'],
        ];
        if ($this->_inline) {
            $options['extraPlugins'] = 'sourcedialog';
            $options['removePlugins'] = 'sourcearea';
        }
        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }

    private function presetIcms()
    {
        $options['height'] = 400;

        $options['toolbarGroups'] = [
            ['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
            ['name' => 'editing', 'groups' => ['find', 'spellchecker', 'tools', 'about']],
            ['name' => 'links', 'groups' => ['links', 'insert']],
            '/',
            ['name' => 'styles'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors', 'cleanup']],
            ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
            ['name' => 'others'],
        ];
        $options['extraPlugins'] = 'quicktable';
        $options['removePlugins'] = 'smiley,specialchar,pagebreak,templates,about';
        if ($this->_inline) {
            $options['extraPlugins'] .= ',sourcedialog';
            $options['removePlugins'] .= ',sourcearea';
        }
        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);

        if ($this->useCKFinder) {
            $this->registerCKFinder();
        }
        if ($this->useCKFSYS) {
            $this->registerCKFSYS();
        }
    }

    protected function registerCKFinder()
    {

        \Yii::$app->session->set('KCFINDER', [
            'disabled' => !\Yii::$app->user->can('manager')
        ]);

        $browseOptions = [
            'filebrowserBrowseUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/browse.php?opener=ckeditor&type=files',
            'filebrowserImageBrowseUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/browse.php?opener=ckeditor&type=images',
            'filebrowserFlashBrowseUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/browse.php?opener=ckeditor&type=flash',
            'filebrowserUploadUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/upload.php?opener=ckeditor&type=files',
            'filebrowserImageUploadUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/upload.php?opener=ckeditor&type=images',
            'filebrowserFlashUploadUrl' => \Yii::getAlias('@web') . '/icms/kcfinder/upload.php?opener=ckeditor&type=flash'
        ];

        $this->editorOptions = ArrayHelper::merge($browseOptions, $this->editorOptions);
    }

    protected function registerCKFSYS()
    {
        \Yii::$app->session->set('CKFSYS_ENABLED', \Yii::$app->user->can('manager'));

        $browseOptions = [
            'filebrowserBrowseUrl' => \Yii::getAlias('@web') . '/icms/ckfsys/browser/default/browser.html?Connector=' . \Yii::getAlias('@web') . '/icms/ckfsys/connectors/php/connector.php',
            'filebrowserImageBrowseUrl' => \Yii::getAlias('@web') . '/icms/ckfsys/browser/default/browser.html?type=Image&Connector=' . \Yii::getAlias('@web') . '/icms/ckfsys/connectors/php/connector.php',
            'filebrowserFlashBrowseUrl' => \Yii::getAlias('@web') . '/icms/ckfsys/browser/default/browser.html?type=Flash&Connector=' . \Yii::getAlias('@web') . '/icms/ckfsys/connectors/php/connector.php',
        ];

        $this->editorOptions = ArrayHelper::merge($browseOptions, $this->editorOptions);
    }

    public function run()
    {
        Assets::register($this->getView());

        echo Html::beginTag('div', $this->containerOptions);
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }

        echo Html::endTag('div');
        $js = [
            'icms.ckEditor.registerOnChange(' . Json::encode($this->options['id']) . ');'
        ];

        if (isset($this->editorOptions['filebrowserUploadUrl']))
            $js[] = "icms.ckEditor.registerCsrf();";

        if (!isset($this->editorOptions['on']['instanceReady']))
            $this->editorOptions['on']['instanceReady'] = new JsExpression("function( ev ){" . implode(' ', $js) . "}");

        if ($this->_inline) {
            $javaScript = "CKEDITOR.inline(";
            $javaScript .= Json::encode($this->options['id']);
            $javaScript .= empty($this->editorOptions) ? '' : ', ' . Json::encode($this->editorOptions);
            $javaScript .= ");";

            $this->getView()->registerJs($javaScript, View::POS_END);
            $this->getView()->registerCss('#' . $this->containerOptions['id'] . ', #' . $this->containerOptions['id'] . ' .cke_textarea_inline{height: ' . $this->editorOptions['height'] . 'px;}');
        } else {
            $javaScript = "if ($('#cke_{$this->options['id']}').length === 0) {";

            $javaScript .= "CKEDITOR.replace(";
            $javaScript .= Json::encode($this->options['id']);
            $javaScript .= empty($this->editorOptions) ? '' : ', ' . Json::encode($this->editorOptions);
            $javaScript .= "); }";

            $this->getView()->registerJs($javaScript, View::POS_END);
        }
    }

}
