<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property string $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $checkcode;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email', 'tel'], 'required'],
            [['last_login_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'last_login_ip'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['checkcode','captcha','captchaAction' => 'site/captcha','message' => '验证码错误']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'cookie密钥',
            'password_hash' => '密码',
            'email' => '邮箱',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => 'Last Login Ip',
            'status' => '状态（1正常，0删除',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=>['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE=>['updated_at']
                ],
            ],
        ];
    }
    //使用下面的代码在 user 表中生成和存储每个用户的认证密钥。
    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }



    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    //根据指定的用户ID查找 认证模型类的实例，当你需要使用session来维持登录状态的时候会用到这个方法。
    public static function findIdentity($id)
    {
        return static::findOne(['id'=>$id,]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    //根据指定的存取令牌查找 认证模型类的实例，该方法用于 通过单个加密令牌认证用户的时候（比如无状态的RESTful应用）。
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    //获取该认证实例表示的用户的ID
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    //获取基于 cookie 登录时使用的认证密钥。 认证密钥储存在 cookie 里并且将来会与服务端的版本进行比较以确保 cookie的有效性。
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    //是基于 cookie 登录密钥的 验证的逻辑的实现
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
