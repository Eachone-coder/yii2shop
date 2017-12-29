<?php

use yii\db\Migration;

class m171229_032101_alter_menu_table extends Migration
{
    public function up()
    {
        $this->renameColumn('menu','name','label');
    }

    public function down()
    {
        echo "m171229_032101_alter_menu_table cannot be reverted.\n";

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
