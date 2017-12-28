<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171228_082544_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'parent_id'=>$this->integer()->notNull()->comment('上级菜单'),
            'url'=>$this->string(50)->notNull()->comment('地址/路由'),
            'sort'=>$this->integer()->notNull()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
