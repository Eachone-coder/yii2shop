<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\Goods;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller{
    public $enableCsrfValidation=false;

    public function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);
// 从项目实例中得到服务端应用实例。
        $server = $app->server;

        $server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    if ($message->Event=='CLICK'){
                        switch ($message->EventKey){
                            case 'active':
                                $articles=Article::find()->orderBy(['create_time'=>'desc'])->limit(5)->select('id,name,intro')->all();
                                $rows=[];
                                foreach ($articles as $article){
                                    $news = new News([
                                        'title'=> $article->name,
                                        'description'=>$article->intro,
                                        'url'=> Url::to(['article/show','id'=>$article->id],true),
                                    ]);
                                    $rows[]=$news;
                                }
                                return $rows;
                                break;
                        }
                    }elseif ($message->Event=='LOCATION'){
                        /*//请求天气
                        //使用simplexml_load_file
                        $xmlObj=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                        $citys=[];
                        foreach ($xmlObj->city as $cityNode){
                            $city=(string)$cityNode['cityname'];
                            $citys[$city]=[
                                'stateDetailed'=>(string)$cityNode['stateDetailed'],
                                'windState'=>(string)$cityNode['windState'],
                                'temNow'=>(string)$cityNode['temNow'],
                            ];
                        }
                        $text = new Text();

                        $find=$citys[$keyword];
                        $s=$find['stateDetailed'];
                        $w=$find['windState'];
                        $t=$find['temNow'];
                        $msg="您查询的城市为:{$keyword}　　　　　　　　　　今天天气状况为:{$s}　　　　　　　　风级状况为:{$w}　　　　　　　　　　　　当前温度为:{$t}";
                        $msg=isset($find)?$msg:'您输入有误';
                        $text->content = $msg;
                        return $text;*/

                        break;
                    }
                    break;
                case 'text':
                    $keywords=$message->Content[0];
                    $content=mb_substr($message->Content,1);
                    if (strtolower($keywords)=='w'){
                        //关于天气
                        return $this->weather($content);
                    }
                    elseif(strtolower($keywords)=='c'){
                        //关于吃
                        $redis=new \Redis();
                        $redis->open('127.0.0.1','6379');
                        if ($redis->exists('location_'.$message->FromUserName)){
                            //存在定位
                            $row=$redis->hGetAll('location_'.$message->FromUserName);
                            $jsonStr=file_get_contents("http://api.map.baidu.com/place/v2/search?query={$content}&location={$row['x']},{$row['y']}&radius=2000&output=json&ak=BtUj6uYjVnsImZ7fcMb41vtgRLOPPPd0&scope=2&page_size=8&scope=2");
                            $objJson=json_decode($jsonStr);
                            //return $objJson->message;
                            $rows=[];
                            $images=[
                                'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=2434111951,3213234429&fm=27&gp=0.jpg',
                                'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1516438770248&di=eb1e27b84177a1a107f909c123ba1238&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fbaike%2Fs%253D220%2Fsign%3Dd5001bcb9245d688a702b5a694c07dab%2F0b55b319ebc4b745555465eecffc1e178b821501.jpg'
                            ];
                            //return $objJson->total;
                            foreach ($objJson->results as $result){
                                $news = new News([
                                    'title'=> $result->name,
                                    'url'=> $result->detail_info->detail_url,
                                    'image'=>$images[mt_rand(0,1)],
                                ]);
                                $rows[]=$news;
                            }
                            return $rows;
                        }else{
                            //不存在定位
                            return '不存在定位';
                        }
                    }
                    elseif(strtolower($keywords)=='n'){
                        return $this->newGoods();
                    }
                    break;
                case 'image':
                    $text = new Image();
                    $text->media_id = $message->MediaId;
                    return $text;
                    break;
                case 'voice':

                    return $message->Recognition;
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':

                    $redis=new \Redis();
                    $redis->open('127.0.0.1','6379');
                    $redis->hMset('location_'.$message->FromUserName,[
                        'x'=>$message->Location_X,//纬度
                        'y'=>$message->Location_Y,//精度
                        'label'=>$message->Label,
                    ]);
                    $redis->setTimeout('location_'.$message->FromUserName,60);
                    return '以更换为当前位置,请输入检索的关键词';
                    break;
                case 'link':
                    return '链接';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        $response = $server->serve();

        $response->send(); // Laravel 里请使用：return $response;
    }

    /**
     * 创建菜单
     */
    public function actionSetMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "view",
                "name" => "在线商城",
                "url"  => "http://shop.eachone.top/index.html"
            ],
            [
                "type" => "click",
                "name" => "最新活动",
                "key"  => "active"
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => "http://shop.eachone.top/wechat/order.html"
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账号",
                        "url" => "http://shop.eachone.top/wechat/login.html"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
    }

    /**
     * @return \EasyWeChat\Support\Collection
     */
    public function actionGetMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $menus = $menu->all();
        return $menus;
    }

    /**
     * @param $keyword
     * @return Text
     */
    public function weather($keyword){
        //请求天气
        //使用simplexml_load_file
        $xmlObj=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
        $citys=[];
        foreach ($xmlObj->city as $cityNode){
            $city=(string)$cityNode['cityname'];
            $citys[$city]=[
                'stateDetailed'=>(string)$cityNode['stateDetailed'],
                'windState'=>(string)$cityNode['windState'],
                'temNow'=>(string)$cityNode['temNow'],
            ];
        }
        $text = new Text();

        $find=$citys[$keyword];
        $s=$find['stateDetailed'];
        $w=$find['windState'];
        $t=$find['temNow'];
        $msg="您查询的城市为:{$keyword}　　　　　　　　　　今天天气状况为:{$s}　　　　　　　　风级状况为:{$w}　　　　　　　　　　　　当前温度为:{$t}";
        $msg=isset($find)?$msg:'您输入有误';
        $text->content = $msg;
        return $text;
    }

    public function newGoods(){
        $goods=Goods::find()->orderBy(['create_time'=>'desc'])->limit(8)->select('id,name,sn,logo')->all();
        $rows=[];
        foreach ($goods as $good){
            $news = new News([
                'title'=> $good->name,
                'description'=>$good->sn,
                'image'=>$good->logo,
                'url'=> Url::to(['goods/goods','id'=>$good->id],true),
            ]);
            $rows[]=$news;
        }
        return $rows;
    }

    /**
     * 网页授权测试
     */
    public function actionTest(){
        //发起授权
        $app = new Application(\Yii::$app->params['wechat']);
        $response = $app->oauth->redirect();
        $response->send(); // Laravel 里请使用：return $response;
    }

    /**
     * 网页授权测试
     * @return string
     */
    public function actionCallback(){
        //获取已授权用户
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        \Yii::$app->session->set('open_id',$user->getId());
        return $this->redirect(Url::to(['wechat/login']));
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
    }

    public function actionOrder()
    {
        /*
         * 先判断是否登录,登录显示订单,未登录跳转登录
         */
        if (\Yii::$app->user->isGuest){
            $url=Url::to(['wechat/order']);
            Url::remember($url,'pre_page');
            return $this->redirect(Url::to(['wechat/login']));
        }
        return '订单';
    }

    public function actionLogin(){
        /*//获取open_id
        if (\Yii::$app->session->has('open_id')){
            $open_id=\Yii::$app->session->get('open_id');
        }else{
            //发起授权
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->redirect();
            $response->send(); // Laravel 里请使用：return $response;
        }

        $redis=new \Redis();
        $redis->open('127.0.0.1','6379');
        //判断用户是否绑定open_id   open_id=>user_id
        //检查该openid是否绑定账号,如果已绑定，自动登录。否则，帮用户绑定账号（登录）
        $user_id=$redis->hGet('open_id',$open_id);
        if ($user_id){
            //已绑定账号
            $member = Member::findOne(['id'=>$user_id]);
            \Yii::$app->user->login($member);
            //取出之前保存的地址
            $url=Url::previous('pre_page');
            return $this->redirect($url);
        }else{
            //未绑定账号,展示登录表单
            $model=new LoginForm();

            if (\Yii::$app->request->isPost){

            }*/
            return $this->render('login');
        //}
    }
}