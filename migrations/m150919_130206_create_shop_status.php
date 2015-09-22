<?php

use yii\db\Migration;

class m150919_130206_create_shop_status extends Migration
{
    public function up()
    {
        $this->createTable('shop_status', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
        
        $this->insert('shop_status', [
            'name' => 'активный'
        ]);
    }

    public function down()
    {
        $this->dropTable('shop_status');
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
