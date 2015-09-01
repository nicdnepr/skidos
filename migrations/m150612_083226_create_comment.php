<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\User;

class m150612_083226_create_comment extends Migration
{
    public function up()
    {
        $this->createTable('comment', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'ид магазина'",
            'author_id'  => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'ид автора комментария'",
            'message'    => Schema::TYPE_TEXT    . ' NOT NULL',
            'answer'     => Schema::TYPE_TEXT,
            
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
        
        $this->addForeignKey('fk_comment_author', 'comment', 'author_id', 'user', 'id');
        $this->addForeignKey('fk_comment_shop', 'comment', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropTable('comment');
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
