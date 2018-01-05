<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m180104_152415_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
            'name'=>$this->string(50)->notNull()->comment('收货人'),
            'province'=>$this->string(20)->notNull()->comment('省'),
            'city'=>$this->string(20)->notNull()->comment('市'),
            'area'=>$this->string(20)->notNull()->comment('县'),
            'address'=>$this->string(255)->notNull()->comment('详细地址'),
            'tel'=>$this->string(11)->notNull()->comment('电话号码'),
            'delivery_id'=>$this->integer()->notNull()->comment('配送方式id'),
            'delivery_name'=>$this->string()->notNull()->comment('配送方式名称'),
            'delivery_price'=>$this->decimal(10,2)->notNull()->comment('配送方式价格'),
            'payment_id'=>$this->integer()->notNull()->comment('支付方式id'),
            'payment_name'=>$this->string()->notNull()->comment('支付方式名称'),
            'total'=>$this->decimal(10,2)->notNull()->comment('订单金额'),
            'status'=>$this->integer()->notNull()->comment('订单状态（0已取消1待付款2待发货3待收货4完成）'),
            'trade_no'=>$this->string()->notNull()->comment('第三方支付交易号'),
            'create_time'=>$this->integer()->notNull()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
