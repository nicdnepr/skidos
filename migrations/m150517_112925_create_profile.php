<?php

use yii\db\Schema;
use yii\db\Migration;

class m150517_112925_create_profile extends Migration
{
    public function up()
    {
        $this->createTable('profile', [
            'id'                => Schema::TYPE_PK,
            'user_id'           => Schema::TYPE_INTEGER . ' NOT NULL',
            'url'               => Schema::TYPE_STRING . ' NOT NULL',
            'recommender_bonus' => Schema::TYPE_SMALLINT . " NOT NULL COMMENT '% бонус для рекомендателя'",
            'buyer_bonus'       => Schema::TYPE_SMALLINT . " NOT NULL COMMENT '% бонус для покупателя'",
        ]);
        
        $this->addForeignKey('fk_profile_user', 'profile', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropTable('profile');
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
