<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    //添加语言
    'language'=>'zh-CN',

    //默认路由
    //'defaultRoute' => 'book',

    'bootstrap' => ['log'],
    'modules' => [
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/Uploads/article',
            'uploadUrl' => 'http://www.admin.shop.com',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            //指定实现认证接口的类 一般就是账号对应的类
            //common
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,      //启用自动登录
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            //设置默认的登录地址
           'loginUrl' => ['login/index'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'class'=>\yii\web\UrlManager::className(),  //指定实现类
            'enablePrettyUrl' => true,      //开启URL美化
            'showScriptName' => false,      //是否显示index.php
            //'suffix'=>'html',
            'rules' => [
                //配置规则
                //'add'=>'goods/add',
            ],
        ],

    ],
    'params' => $params,
];
