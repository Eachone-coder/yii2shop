<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m180106_085028_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->notNull()->comment('订单id'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'goods_name'=>$this->string()->notNull()->comment('商品名称'),
            'logo'=>$this->string()->notNull()->comment('图片'),
            'price'=>$this->decimal(10,2)->notNull()->comment('价格'),
            'amount'=>$this->integer()->notNull()->comment('数量'),
            'total'=>$this->decimal(10,2)->notNull()->comment('小计'),
        ],'CHARACTER SET utf8 ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
