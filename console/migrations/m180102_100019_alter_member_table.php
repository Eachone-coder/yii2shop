<?php

use yii\db\Migration;

class m180102_100019_alter_member_table extends Migration
{
    public function up()
    {
        $this->alterColumn('member','last_login_ip','string(50)');
    }

    public function down()
    {
        echo "m180102_100019_alter_member_table cannot be reverted.\n";

        return false;
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
