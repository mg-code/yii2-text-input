<?php
namespace mgcode\textInput;

use yii\helpers\Html;
use yii\base\Model;
use yii\validators\StringValidator;
use yii\widgets\InputWidget;

class TextInput extends InputWidget
{
    /** @inheritdoc */
    public $options = ['class' => 'form-control'];

    public function run()
    {
        $this->normalizeMaxLength();
        $this->registerClientScript();

        $input = $this->renderInput();
        if(!isset($this->options['maxlength'])) {
            return $input;
        }

        $value = $this->hasModel() ? Html::getAttributeValue($this->model, $this->attribute) : $this->value;
        $taken = mb_strlen($value, 'UTF-8');
        $left = $this->options['maxlength'] - $taken;
        return '<div class="input-group" data-field="count-characters">'.$input.'<span class="input-group-addon" data-field="characters-remaining">'.$left.'</span></div>';
    }

    /**
     * Registers JavaScript asset
     */
    protected function registerClientScript()
    {
        TextInputAsset::register($this->getView());
    }

    /**
     * Renders input
     * @return string
     */
    protected function renderInput()
    {
        if($this->hasModel()) {
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::textInput($this->name, $this->value, $this->options);
        }
    }

    /**
     * If the model attribute is validated by a string validator,
     * the `maxlength` option will take the value of [[\yii\validators\StringValidator::max]].
     * @param Model $model the model object
     * @param string $attribute the attribute name or expression.
     */
    protected function normalizeMaxLength()
    {
        if(isset($this->options['maxlength']) && $this->options['maxlength'] !== true) {
            return;
        }

        unset($this->options['maxlength']);
        if(!$this->hasModel()) {
            return;
        }

        $attrName = Html::getAttributeName($this->attribute);
        foreach ($this->model->getActiveValidators($attrName) as $validator) {
            if ($validator instanceof StringValidator && $validator->max !== null) {
                if ($validator->when === null || call_user_func($validator->when, $this->model, $this->attribute)) {
                    $this->options['maxlength'] = $validator->max;
                    return;
                }
            }
        }
        return;
    }
}