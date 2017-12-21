<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',

    //添加语言
    'language'=>'zh-CN',
    //默认路由
    //'defaultRoute' => 'book',


    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            //指定实现认证接口的类 一般就是账号对应的类
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            //设置默认的登录地址
            //'loginUrl' => ['login/index'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'class'=>'yii\web\urlManager',  //指定实现类
            'enablePrettyUrl' => true,      //开启URL美化
            'showScriptName' => false,      //是否显示index.php
            'suffix'=>'html',
            'rules' => [
                //配置规则
                //'add'=>'goods/add',
            ],
        ],
    ],
    'params' => $params,
];