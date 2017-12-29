<?php

namespace backend\filter;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter
{
    public function BeforeAction($action)
    {
        if (!\Yii::$app->user->can($action->uniqueId)) {
            if (\Yii::$app->user->isGuest) {
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new HttpException('403', '你没有该权限');
        }
        return true;
    }
}