<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m171226_153128_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'auth_key'=>$this->string(32)->notNull()->comment('cookie密钥'),
            'password_hash'=>$this->string(100)->notNull()->comment('密码'),
            'email'=>$this->string(100)->notNull()->comment('邮箱'),
            'tel'=>$this->string(11)->notNull()->comment('电话'),
            'last_login_time'=>$this->integer()->notNull()->comment('最后登录时间'),
            'last_login_ip'=>$this->integer()->notNull()->comment('最后登录IP'),
            'status'=>$this->integer(1)->notNull()->comment('状态（1正常，0删除'),
            'created_at'=>$this->integer()->notNull()->comment('添加时间'),
            'updated_at'=>$this->integer()->notNull()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
