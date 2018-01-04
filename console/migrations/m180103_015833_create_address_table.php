<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180103_015833_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
            'name'=>$this->string(50)->notNull()->comment('收货人'),
            'cmbProvince'=>$this->string(10)->notNull()->comment('所在省'),
            'cmbCity'=>$this->string(20)->notNull()->comment('所在市'),
            'cmbArea'=>$this->string(30)->notNull()->comment('所在县'),
            'address'=>$this->string()->notNull()->comment('详情地址'),
            'tel'=>$this->string(11)->notNull()->comment('电话'),
            'is_default'=>$this->integer(1)->notNull()->comment('是否默认地址 	0:否 1:是'),
            'created_at'=>$this->integer()->notNull()->comment('添加时间'),
            'updated_at'=>$this->integer()->notNull()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
