<?php

use yii\db\Migration;

class m150919_130721_update_profile extends Migration
{
    public function up()
    {
        $this->addColumn('profile', 'status_id', $this->integer());
        
        $this->update('profile', [
            'status_id' => 1
        ]);
        
        $this->addForeignKey('status', 'profile', 'status_id', 'shop_status', 'id');
        
        $this->addColumn('profile', 'host', $this->string());
        
        $this->createIndex('host', 'profile', 'host');
    }

    public function down()
    {
        $this->dropForeignKey('status', 'profile');
        
        $this->dropColumn('profile', 'status_id');
        $this->dropColumn('profile', 'host');
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
