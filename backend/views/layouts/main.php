<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '清水商城B2C',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems=[
        [
            'label'=>'品牌',
            'items'=>[
                ['label' => '品牌列表', 'url' =>['/brand/index']],

                ['label' => '添加品牌', 'url' => ['/brand/add']],
            ]
        ],
        [
                'label'=>'文章',
                'items'=>[
                    ['label' => '文章', 'url' => ['/article/index']],
                    ['label' => '文章分类', 'url' => ['/article-category/index']],
                ],
        ],
        [
            'label'=>'商品',
            'items'=>[
                ['label' => '商品列表', 'url' =>['/goods/index']],
                ['label' => '商品分类', 'url' => ['/goods-category/index']],
            ]
        ],
        [
            'label'=>'管理员',
            'items'=>[
                ['label' => '管理员列表', 'url' => ['/user/index']],
                ['label' => '权限管理', 'url' => ['/brand/add']],
            ]
        ],
        [
            'label'=>'回收站',
            'items'=>[
                ['label' => '文章回收站', 'url' => ['/trash/index']],
                ['label' => '品牌回收站', 'url' => ['/trash/index']],
            ]
        ],
        ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/login/index']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/login/logout'], 'post')
            . Html::submitButton(
                '退出 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 清水商城B2C <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
