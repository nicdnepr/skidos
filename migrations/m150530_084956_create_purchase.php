<?php

use yii\db\Schema;
use yii\db\Migration;

class m150530_084956_create_purchase extends Migration
{
    public function up()
    {
        $this->createTable('purchase', [
            'id'           => Schema::TYPE_PK,
            'user_id'      => Schema::TYPE_INTEGER,
            'affiliate_id' => Schema::TYPE_INTEGER,
            'shop_id'      => Schema::TYPE_INTEGER,
            'url_id'       => Schema::TYPE_INTEGER,
            'sum'          => Schema::TYPE_DECIMAL . '(10, 2) NOT NULL',
            'status'       => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            
            'created_at'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'   => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);
        
        $this->addForeignKey('fk_purchase_user', 'purchase', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_purchase_affiliate', 'purchase', 'affiliate_id', 'user', 'id');
        $this->addForeignKey('fk_purchase_shop', 'purchase', 'shop_id', 'user', 'id');
        $this->addForeignKey('fk_purchase_url', 'purchase', 'url_id', 'url', 'id');
    }

    public function down()
    {
        $this->dropTable('purchase');
        
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
