<?php

use yii\db\Migration;

class m150919_120346_create_moderate_shop extends Migration
{
    public function up()
    {
        $this->createTable('moderate_shop', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'url' => $this->string()->unique(),
            'created_at' => $this->dateTime()
        ]);
    }

    public function down()
    {
        $this->dropTable('moderate_shop');
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
