<?php

use yii\db\Migration;

class m150922_130105_update_purchase extends Migration
{
    public function up()
    {
        $this->addColumn('purchase', 'url', $this->string());
    }

    public function down()
    {
        $this->dropColumn('purchase', 'url');
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
