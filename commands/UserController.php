<?php

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use app\models\Url;
use app\models\Profile;

class UserController extends Controller
{
    /* @var $this->auth \yii\rbac\DbManager */
    private $auth;
    
    public function actionInit()
    {
        $this->auth = \Yii::$app->authManager;
        
        $this->createUser('user1');
        $this->createUser('user2');
        
        $shop = $this->createShop('shop1', 10, 20);
        $this->createUrl($shop->id, 'https://temp-mail.ru/', 'фывфвфывфыв');
        $this->createUrl($shop->id, 'http://forum.china-iphone.ru/', '234цукыва');
        
        $shop = $this->createShop('shop2', 30, 40);
        $this->createUrl($shop->id, 'https://teываываmp-mail.ru/', 'фывфвфцукывфыв');
        $this->createUrl($shop->id, 'http://for32432432um.china-iphone.ru/', '234цуцукцукыва');
        $this->createUrl($shop->id, 'https://teцукцукцmp-mail.ru/', 'фывфвцукцуфывфыв');
        $this->createUrl($shop->id, 'http://forum.china-iphуцкуцкуцкцукone.ru/', '234ццукуцкукыва');
    }
    
    private function createUser($login)
    {
        $user = new User;
        $user->email = $login;
        $user->setPassword($user->email);
        $user->generateAuthKey();
        $user->save(false);
        
        $this->auth->assign($this->auth->createRole(User::ROLE_USER), $user->id);
        
        return $user;
    }
    
    private function createShop($login, $buyer_bonus, $recommender_bonus)
    {
        $user = new User;
        $user->email = $login;
        $user->setPassword($user->email);
        $user->generateAuthKey();
        $user->save(false);
        
        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->url = 'https://temp-mail.ru';
        $profile->buyer_bonus = $buyer_bonus;
        $profile->recommender_bonus = $recommender_bonus;
        $profile->save(false);
        
        $this->auth->assign($this->auth->createRole(User::ROLE_SHOP), $user->id);
        
        return $user;
    }
    
    private function createUrl($user_id, $link, $name)
    {
        $url = new Url;
        $url->user_id = $user_id;
        $url->link = $link;
        $url->name = $name;
        $url->save(false);
    }
}