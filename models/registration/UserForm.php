<?php

namespace app\models\registration;

use Yii;
use app\models\User;

class UserForm extends BaseForm
{
    public $phone;
    
    public function rules()
    {
        $rules = [
            ['phone', 'trim'],
            ['phone', 'filter', 'filter' => function($value){ return str_replace('+', '', $value); }],
            ['phone', 'string', 'min' => 11, 'max' => 12]
        ];
        
        return \yii\helpers\ArrayHelper::merge(parent::rules(), $rules);
    }
    
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон'
        ];
    }
    
    public function save()
    {
        $user = new User;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->password = $user->generatePassword();
        $user->setPassword($user->password);
        $user->generateAuthKey();
        $user->save(false);
        
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_USER), $user->id);

        
        Yii::$app->mailer->compose('registration/user', ['model'=>$user])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($this->email)
            ->setSubject('Регистрация пользователя')
            ->send();
        
        return $user;
        
    }
}