<?php

namespace backend\controllers;

use yii\web\Controller;
use backend\filter\RbacFilter;

class BaseController extends Controller
{
    /**
     * RBAC权限控制
     * @return array
     */
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => RbacFilter::className(),
                'except' => ['index', 'logout', 'upload', 'captcha', 'ueditor'],
            ],
        ];
    }
}