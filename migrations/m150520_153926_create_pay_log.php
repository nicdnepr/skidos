<?php

use yii\db\Schema;
use yii\db\Migration;

class m150520_153926_create_pay_log extends Migration
{
    public function up()
    {
        $this->createTable('pay_log', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'sum'        => Schema::TYPE_DECIMAL . '(10, 2) NOT NULL',
            'type'       => Schema::TYPE_SMALLINT . ' NOT NULL',
            'status'     => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
        
        $this->addForeignKey('fk_paylog_user', 'pay_log', 'user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropTable('pay_log');
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
