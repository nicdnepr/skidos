<?php

namespace app\models\registration;

class BaseForm extends \yii\base\Model
{
    public $email;
    
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
        ];
    }
}