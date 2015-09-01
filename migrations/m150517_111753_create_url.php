<?php

use yii\db\Schema;
use yii\db\Migration;

class m150517_111753_create_url extends Migration
{
    public function up()
    {
        $this->createTable('url', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'link'       => Schema::TYPE_STRING . ' NOT NULL',
            'name'       => Schema::TYPE_STRING,
            
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
        
        $this->addForeignKey('fk_url_user', 'url', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropTable('url');
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
