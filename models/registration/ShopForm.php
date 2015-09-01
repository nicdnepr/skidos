<?php

namespace app\models\registration;

use Yii;
use app\models\User;
use app\models\Url;

class ShopForm extends BaseForm
{
    public $password;
    
    public function rules()
    {
        $rules = [
            ['password', 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['email', 'unique', 'targetClass' => '\app\models\User'],
        ];
        
        return \yii\helpers\ArrayHelper::merge(parent::rules(), $rules);
    }
    
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }
    
    public function save($profile, $urls)
    {
        $user = new User;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->setPassword($user->password);
        $user->generateAuthKey();
        $user->save(false);
        
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole(User::ROLE_SHOP), $user->id);

        $profile->user_id = $user->id;
        $profile->save(false);

        $url = new Url;
        $url->user_id = $user->id;
        $url->link = $profile->url;
        $url->name = 'Главная страница';
        $url->save(false);

        if (is_array($urls)) {
            foreach ($urls as $item) {
                if (is_array($item)) {
                    $url = new Url;
                    $url->user_id = $user->id;
                    $url->link = $item['link'];
                    $url->name = $item['name'];
                    $url->save(false);
                }
            }
        }
        
        Yii::$app->mailer->compose('registration/shop', ['model'=>$user])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setTo($this->email)
            ->setSubject('Регистрация магазина')
            ->send();
        
    }
}