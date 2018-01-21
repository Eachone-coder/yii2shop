<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wechat`.
 */
class m180120_052132_create_wechat_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('wechat', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('wechat');
    }
}
