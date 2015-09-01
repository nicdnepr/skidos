<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\User;

class m150517_100812_create_user extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id'                   => Schema::TYPE_PK,
            'email'                => Schema::TYPE_STRING . ' NOT NULL',
            'phone'                => Schema::TYPE_STRING . '(20)',
            'balance'              => Schema::TYPE_DECIMAL . '(10, 2) NOT NULL DEFAULT 0',
            'auth_key'             => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash'        => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'rating'               => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            
            'created_at'           => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'           => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
        
        $this->createIndex('email', 'user', 'email', true);
        $this->createIndex('phone', 'user', 'phone');
        
        $this->initRbac();
    }

    public function down()
    {
        $this->dropTable('user');
    }
    
    private function initRbac()
    {
        Yii::$app->cache->flush();
        
        $auth = Yii::$app->authManager;
        
        $rule = new \app\rbac\OwnerRule;
        $auth->add($rule);
        
        $accessUrl = $auth->createPermission('accessUrl');
        $accessUrl->description = 'удаление\редактировние записей урл';
        $auth->add($accessUrl);
        
        $accessOwnUrl = $auth->createPermission('accessOwnUrl');
        $accessOwnUrl->description = 'удаление\редактирование своих записей урл';
        $accessOwnUrl->ruleName = $rule->name;
        $auth->add($accessOwnUrl);
        $auth->addChild($accessOwnUrl, $accessUrl);
        
        $accessComment = $auth->createPermission('accessComment');
        $accessComment->description = 'удаление\редактировние комментариев';
        $auth->add($accessComment);
        
        $accessOwnComment = $auth->createPermission('accessOwnComment');
        $accessOwnComment->description = 'удаление\редактирование комментариев, относящихся к конкретному магазину';
        $accessOwnComment->ruleName = $rule->name;
        $auth->add($accessOwnComment);
        $auth->addChild($accessOwnComment, $accessComment);
        
        $user = $auth->createRole(User::ROLE_USER);
        $auth->add($user);
        
        $shop = $auth->createRole(User::ROLE_SHOP);
        $auth->add($shop);
        $auth->addChild($shop, $accessOwnUrl);
        $auth->addChild($shop, $accessOwnComment);
        
        $admin = $auth->createRole(User::ROLE_ADMIN);
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $shop);
        $auth->addChild($admin, $accessUrl);
        $auth->addChild($admin, $accessComment);
        
        
        $user = new User();
        $user->email = 'admin';
        $user->setPassword('adminadmin');
        $user->generateAuthKey();
        $user->save(false);
        
        $auth->assign($admin, $user->id);
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
