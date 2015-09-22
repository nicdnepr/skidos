<?php

use yii\db\Migration;

class m150922_125052_update_url extends Migration
{
    public function up()
    {
        $this->addColumn('url', 'shop_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('url', 'shop_id');
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
