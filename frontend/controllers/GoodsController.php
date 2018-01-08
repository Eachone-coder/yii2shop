<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller{
    public $enableCsrfValidation=false;
    /**
     * 商品三级分类查询
     * @param $id
     * @return string
     */
    public function actionGoodsCategory($id){
        $row=GoodsCategory::findOne(['id'=>$id]);
        if ($row->depth==2){
            $ids=$row->id;
        }else{
            $ids=$row->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->all();
            $ids=ArrayHelper::map($ids,'id','id');
        }
        $goods=Goods::find()->where(['in','goods_category_id',$ids])->all();
        return $this->render('list',['goods'=>$goods]);
    }

    /**
     * 商品具体详情
     * @param $id
     * @return string
     */
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        $goodsGallery=GoodsGallery::findAll(['goods_id'=>$id]);
        Goods::updateAllCounters(['view_times'=>1],['id'=>$id]);
        $first=array_shift($goodsGallery);
        return $this->render('goods',['goods'=>$goods,'goodsIntro'=>$goodsIntro,'goodsGallery'=>$goodsGallery,'first'=>$first]);
    }


    /**
     * @return string
     */
    public function actionSearch(){
        $request=\Yii::$app->request;
        //var_dump($request->get('keywords'));
        $goods=Goods::find()->where(['LIKE','name',$request->get('keywords')])->all();
        return $this->render('list',['goods'=>$goods]);
    }
    //添加购物车成功提示页面

    /**
     * @param $pid
     * @param $amount
     * @return string
     */
    public function actionAddToCart($pid, $amount){
        /*
        思路:
            1.先判断是否登录,如果登录了,就保存到数据表;没登录保存到cookie,登陆后迁移到数据库
            2.没登录,先判断是否有之前是否已经存了相同的数据,没有就新增一个,有就取出;有又分两种情况,相同id和不同id
                存cookie的格式  [goods_id=>amount,goods_id=>amount,...]
        */
        if (\Yii::$app->user->isGuest){
            //未登录
            //先读cookie,在写cookie
            $readCookie=\Yii::$app->request->cookies;
            if ($readCookie->has('cart')){
                //之前已存cookie
                $product=unserialize($readCookie->getValue('cart'));
            }else{
                $product=[];
            }

            if (array_key_exists($pid,$product)){
                $product[$pid]+=$amount;
            }else{
                $product[$pid]=$amount;
            }
            //写cookie
            $writeCookie=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='cart';
            $cookie->value=serialize($product);
            $writeCookie->add($cookie);
        }
        else{
            //已登录
            $model=Cart::findOne(['goods_id'=>$pid,'member_id'=>\Yii::$app->user->identity->getId()]);
            if (!$model){
                $model=new Cart();
                $model->goods_id=$pid;
                $model->amount=$amount;
                $model->member_id=\Yii::$app->user->identity->getId();
            }
            else{
                $model->amount+=$amount;
            }
            if ($model->validate()){
                $model->save();
            }else{
                var_dump($model->getErrors());
            }
        }
        //跳转到购物车
        return $this->redirect(['goods/cart']);
    }

    //购物车页面

    /**
     * @return string
     */
    public function actionCart(){
        /*
        判断是否登录
            没登录,读取cookie的数据
            登录,读取数据库的数据
            $rows   cookie中的数据  [goods_id=>amount,goods_id=>amount...]
            $ids    goods_id的集合  [0=>goods_id,1=>goods_id,...]
            $cart   goods_id和id的集合  [goods_id=>id,]
        */
        $rows=[];
        $ids=[];
        $cart=[];
        if (\Yii::$app->user->isGuest){
            //没登录
            $cookie=\Yii::$app->request->cookies;
            $data=$cookie->getValue('cart');
            if ($data){
                $rows=unserialize($data);
                $ids=array_keys($rows);
                $cart=$rows;
            }
        }else{
            //登录
            $array=Cart::find()->where(['member_id'=>\Yii::$app->user->identity->getId()])->all();
            $rows=ArrayHelper::map($array,'goods_id','amount');
            $cart=ArrayHelper::map($array,'goods_id','id');
            $ids=ArrayHelper::map($array,'goods_id','goods_id');
        }
        $model=Goods::find()->where(['in','id',$ids])->all();
        return $this->render('cart',['models'=>$model,'count'=>$rows,'cart'=>$cart]);
    }


    /**
     * @param $id
     * @param $amount
     * @param string $pid
     * @return string
     */
    public function actionEditAmount($id, $amount, $pid=''){
            //根据id修改amount
        if (is_numeric($amount)){
            if (\Yii::$app->user->isGuest){
                $readCookie=\Yii::$app->request->cookies;
                $data=unserialize($readCookie->getValue('cart'));

                $cookies=\Yii::$app->response->cookies;
                $cookie=new Cookie();
                $data[$pid]=$amount;
                $cookie->name='cart';
                $cookie->value=serialize($data);
                $cookies->add($cookie);
            }else{
                Cart::updateAll(['amount'=>$amount],['id'=>$id]);
            }

            return Json::encode(['status'=>'true']);
        }
        else{
            return Json::encode(['status'=>'false']);
        }
    }


    /**
     * @param $id
     * @param string $pid
     */
    public function actionDelAmount($id, $pid=''){
        if (\Yii::$app->user->isGuest){
            $readCookie=\Yii::$app->request->cookies;
            $data=unserialize($readCookie->getValue('cart'));
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            unset($data[$pid]);
            $cookie->name='cart';
            $cookie->value=serialize($data);
            $cookies->add($cookie);
        }else{
            Cart::deleteAll(['id'=>$id]);
        }
    }

    //提交订单页
    public function actionOrder(){
        $model=new Order();

        $address=Address::findAll(['member_id'=>\Yii::$app->user->identity->id]);

        $carts=Cart::findAll(['member_id'=>\Yii::$app->user->identity->id]);
        $ids=ArrayHelper::map($carts,'goods_id','goods_id');
        $amount=ArrayHelper::map($carts,'goods_id','amount');

        $goods=Goods::find()->where(['in','id',$ids])->all();
        $goods_name=ArrayHelper::map($goods,'id','name');
        $goods_logo=ArrayHelper::map($goods,'id','logo');
        $goods_price=ArrayHelper::map($goods,'id','shop_price');

        $request=\Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),'');
            //var_dump($model);die;

            $post_data=$request->post();
            //var_dump($request->post());die;
            //收货地址
            $address=Address::findOne(['id'=>$post_data['address_id']]);
            //var_dump($address);die;
            $model->member_id=\Yii::$app->user->identity->id;
            $model->name=$address->name;
            $model->province=$address->cmbProvince;
            $model->city=$address->cmbCity;
            $model->area=$address->cmbArea;
            $model->address=$address->address;
            $model->tel=$address->tel;
            //配送方式
            $model->delivery_name=Order::$delivery[$model->delivery_id][0];
            $model->delivery_price=Order::$delivery[$model->delivery_id][1];
            //支付方式
            $model->payment_id=1;
            $model->payment_name="支付宝";
            //订单状态
            $model->status=1;
            $model->trade_no='pay001';
            $model->total=0;
            $model->create_time=time();

            //开启事务
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                //var_dump($model);die;
                if ($model->validate()){
                    $model->save();
                }
                else{
                    var_dump($model->getErrors());
                }
                //总金额
                $sum=0;
                //order_goods表
                foreach ($carts as $cart){
                    $goods=Goods::findOne(['id'=>$cart->goods_id]);
                    if ($goods->stock>=$cart->amount){
                        $order_goods=new OrderGoods();
                        $order_goods->order_id=$model->id;
                        $order_goods->goods_id=$cart->goods_id;
                        $order_goods->goods_name=$goods_name[$cart->goods_id];
                        $order_goods->logo=$goods_logo[$cart->goods_id];
                        $order_goods->price=$goods_price[$cart->goods_id];
                        $order_goods->amount=$cart->amount;
                        $order_goods->total=$cart->amount*$goods_price[$cart->goods_id];
                        $sum+=$order_goods->total;
                        $order_goods->save();
                        //
                        $goods->stock-=$order_goods->amount;
                        $goods->save(false);

                        //
                    }else{
                        throw new Exception('库存不足');
                    }
                }
                $model->total=$sum;
                $model->save();
                //清空购物车
                Cart::deleteAll(['member_id'=>\Yii::$app->user->identity->id]);
                //提交事务
                $transaction->commit();
                return $this->redirect(['goods/order-goods']);
            }catch (Exception $exception){
                //事务回滚
                $transaction->rollBack();
            }
        }else{
            return $this->render('order',['address'=>$address,'goods'=>$goods,'amount'=>$amount]);
        }

    }

    /**
     * @return string
     */
    public function actionOrderGoods(){
        return $this->render('order-goods');
    }

    /**
     * @return string
     */
    public function actionOrderList(){
        $model=Order::find()->where(['member_id'=>\Yii::$app->user->identity->id])->all();
        $gallerys=OrderGoods::find()->all();
        return $this->render('order-list',['rows'=>$model,'gallerys'=>$gallerys]);
    }
}