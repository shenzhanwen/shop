<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GoodsController extends Controller
{
    public function goods()
    {
        $data=DB::table('cart')
            ->join('goods','cart.id','=','goods.id')
            ->get();
            $total = 0;
            foreach($data->toArray() as $v){
                $total += $v->buy_num * $v->goods_mon;
            }
           
        return view('goods.goods',['data'=>$data,'total'=>$total]);
    }

    public function proinfo()
    {
        $id=\request()->id;
        $res = DB::table('goods')->where('id',$id)->get();
        return view('goods.proinfo',['res'=>$res]);
    }

    public function check()
    {
        $id = request()->id;
        $buy_num = request()->buy_num;
        $res = DB::table('goods')->where('id',$id)->get();
        $goods_mon=$res[0]->goods_mon;
        $newprice=$buy_num*$goods_mon;
        return $newprice;
    }

    public function addca()
    {
        $data=request()->input();
        // $where=[
        //     'goods_id'=>$data['goods_id'],
        //     'login_id'=>$data['login_id']
        // ];
        // $count=DB::table('cart')->where($where)->count();
        // if ($count>0){
        //     return ['font'=>'该商品已在购物车内','code'=>2];die;
        // }else{
            $res=DB::table('cart')->insert($data);
            
            if ($res){
                return ['code'=>1];die;
            }else{
                return ['code'=>2];die;
            
        }
    }
    
    
    public function getSubTotal()
    {
        $num=request()->goods_num;
        $price=request()->price;
        return $newprice=$num*$price;
    }

    public function del($cart_id)
    {
        $res = request()->all();
       $data = DB::table('cart')->where(['cart_id'=>$cart_id])->delete();
     
       if($data){
           return redirect('goods/goods');
       }
    }

    public function order()
    {
        $res = DB::table('order')->orderBy('order_id','desc')->first();
        $data=DB::table('cart')
        ->join('goods','cart.id','=','goods.id')
        ->get();
        $total = 0;
        foreach($data->toArray() as $v){
            $total += $v->buy_num * $v->goods_mon;
        }
         // if($data['state'] == 1){
        //     return ['未支付'];
        // }else{
        //     return ['未支付'];
        // }
        // $state = 1;
        // $xb = $state == 1 ? '未支付' : ($state == 2 ? '已支付' : '未知');
        // return $xb;
        return view('goods.order',['res'=>$res,'total'=>$total]);
    }

    public function orderadd()
    {
        $res = request()->all();
        $data['end_time'] = time();
        $order_no= time().mt_rand(1000,1111);  //订单编号
        
        $data = DB::table('order')->insert([
            'cart_id'=>$res['cart_id'],
            'end_time'=>time(),
            'order_no'=>$order_no,
        ]);

       
        if($data){
            return(['code'=>1]);
        }else{
            return(['code'=>2]);
        }

    }

    public function dele($order_id){
        $res = DB::table('order')->where(['order_id'=>$order_id])->delete();
        if($res){
            return redirect('goods/goods');
        }
    }
    
        public $app_id;
        public $gate_way;
        public $notify_url;
        public $return_url;
        public $rsaPrivateKeyFilePath = '';  //路径
        public $aliPubKey = '';  //路径
        public $privateKey = "MIIEpAIBAAKCAQEAqv6/NWB6K4HUEbPMCiokp+JtwKxzDSFW1zLsZuLioMEb9FCtueR7v5+vcHHR7iautiCQ9RXo5QKoj2aYhYfqjp9S36idPi42NNbXGGi5Nzx2OVBsc/TNl5ajjRoNzQ0WSJ+nnj04vKau0rHJa0cU/Wny5jAb340zdNVyvDDG6abM4TrRs5KWeCN/jRiXCa6tKeh1KXPrkUFBdnlqviBAKU+f3TjsFAjGNeaNi0RCMwEZl2faxEgmJN4eW+ZvSe+pLWGuOFNOP4Wo8e912jc9NtBZSMYt6iNGTNcleUzzlumbS4DR/9ycFZcYNhnOTJRJYHJJyByTxaQ12EtNU4I6ZQIDAQABAoIBAQCpuXXc60gYSyNN9uK97Bq6MPuzVPhVvhPOQpN3hBhia4rts+7cJrfV+hBPUgqHkgm0aRq4yeiC92KMA8+8Kq5cpjPCIEEDaScQx3Z0me3Nr8vBE9yyM/ilUDZCZevw7d6Cc0YSwL3CMsgwBZvhSYrCBhm89V+VQzgh7YOW2+t0GiXUtlicb+N8mr43dqLKcPiVgxDesehywPWVBG9egIEljblPrIZqQhtwtzHP0al3Qsvu8DjV9koSgwB+1xW0eBbh2Biwc3e8COHRmOmbQNINYND+BdWbLmQaJMigDZFwXFa5Xc/SZjOLjtpraZtwOhqpMgg3MTBSVqSBSHoeQCu5AoGBAN/LbQYZqG/lU6P0rHrTfNYnsGZ7hd7GhxeT/HTWcAV7V3Kw+X+SUTJu6s1VrHMsinLXx5+GLh+lFYTDZ/kURfvemeg6RJoo0So3ZZRll35z/efOgNYvm9YVcFkWSeG3svr9XesVy7WKS5sxJiXkWo9bTCX1ltQ+taewWSJb4qJfAoGBAMOaMPKDACZqBC4a2uA9Q3+BnkRWCGeunWLqyLFKfe2Jdf8rhDL7uq/7tTXDwDZx4E4hqC6P2BVHCcxdB/7DWNyIgF/pt+8tYOw36yz1zIyDOtlQj1SPf8BW+drLh0tmf63oQPLEW0nSAVRBTDGi6ZkDL7iZvo3bG2Ruz7gq9MG7AoGBAIoqYHPssVXr4liN/VKTcTYSKNEMn9iq/spqvekwXtazkIiphE/jeKfHXMVKiH5O2GcQmxj80UsOM5vUvENF5fMbOi/qkWIPoxsKOneExSP33qEXl+kkWXSxpbwKi7CEJkbY1/wEZ9D7wbPdgyzPr61j8z5YQjdlQ+d0c4NH8ULHAoGAWxHCMlcdzcTUd2bXe0nq0EXYGzb3KNbavkBT+n+VS/AROSHYpigfzmnh/8tJBdBVP+tZs3wTfiyZ+kViQJJRIDSJmJ5nnftBwhly7Hbwqoc68g0Y/qTlNPcJ032pW0i4r46QFrenTSJyrfjM8dtI6Y3bPRaP6toV0dfp7HRLipsCgYB8ncOdpMxf17o2aN/HJhW5erRoEvzq/G1JIZ5tHcEaTOoGyZi8+oQts+OrdkWjPaDqTZREJFW4QU2aqUEdADlKccwsCXW4n7xFmh6qYXkMGZH7qjxb15aGgSpEzF0yYE8kbkHCd7WDSRn6u+N/MydMPmULc1Kp4Rf0qHaxZ7hynQ==";
        public $publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjz/EFIk4MrA6ncDhwxmiulZ4kx1vpDwImg5OiXFBZyyPTHNLKmltsbcthdS7hEWbfbfRe2okCNxUlOym9V4wr5hqdObNfbs5NO4OsKmB+Uhi2dDIscaRFFUSUqPxDJesods/XeoYj10KDoGighi5yDPFWedurxtnAUGqQ7hnFrAiNeVvBz0XsqoB05jn4y9A/LkbMUizsVMrCP2mzsk3+hhZ5F1FDD5m7sjSW6qA8nASktr0eFDplCstQbiQimc9ln1p+FhLiujuIb6X8YRNiHzQYn+e6+I8pYso8xMKbJiDMSFBKqQndCYLJ2inPZiYfjPKN0gS24yjo9Ed1N6eQwIDAQAB";
        public function __construct()
        {
            $this->app_id = '2016100100638344';
            $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
            $this->notify_url = env('APP_URL').'/notify_url';
            $this->return_url = env('APP_URL').'/return_url';
        }
        
        
        /**
         * 订单支付
         * @param $oid
         */
        public function pay()
        {
            // file_put_contents(storage_path('logs/alipay.log'),"\nqqqq\n",FILE_APPEND);
            // die();
            //验证订单状态 是否已支付 是否是有效订单
            //$order_info = OrderModel::where(['oid'=>$oid])->first()->toArray();
            //判断订单是否已被支付
            // if($order_info['is_pay']==1){
            //     die("订单已支付，请勿重复支付");
            // }
            //判断订单是否已被删除
            // if($order_info['is_delete']==1){
            //     die("订单已被删除，无法支付");
            // }
            $res =DB::table('order')->get();  //订单编
            $oid=0;
            foreach($res->toArray() as $v){
                $oid=$v->order_no;
            }
            $date=DB::table('cart')
            ->join('goods','cart.id','=','goods.id')
            ->get();
            $total= 0;
            foreach($date->toArray() as $v){
                    $total += $v->buy_num * $v->goods_mon;
            }
            //业务参数
            $bizcont = [
                'subject'           => 'Lening-Order: ' . $oid,
                'out_trade_no'      => $oid,
                'total_amount'      => $total,
                'product_code'      => 'FAST_INSTANT_TRADE_PAY',
            ];
            //公共参数
            $data = [
                'app_id'   => $this->app_id,
                'method'   => 'alipay.trade.page.pay',
                'format'   => 'JSON',
                'charset'   => 'utf-8',
                'sign_type'   => 'RSA2',
                'timestamp'   => date('Y-m-d H:i:s'),
                'version'   => '1.0',
                'notify_url'   => $this->notify_url,        //异步通知地址
                'return_url'   => $this->return_url,        // 同步通知地址
                'biz_content'   => json_encode($bizcont),
            ];
            //签名
            $sign = $this->rsaSign($data);
            $data['sign'] = $sign;
            $param_str = '?';
            foreach($data as $k=>$v){
                $param_str .= $k.'='.urlencode($v) . '&';
            }
            $url = rtrim($param_str,'&');
            $url = $this->gate_way . $url;
            
            header("Location:".$url);
        }
        public function rsaSign($params) {
            return $this->sign($this->getSignContent($params));
        }
        protected function sign($data) {
            if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
                $priKey=$this->privateKey;
                $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                    wordwrap($priKey, 64, "\n", true) .
                    "\n-----END RSA PRIVATE KEY-----";
            }else{
                $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
                $res = openssl_get_privatekey($priKey);
            }
            
            ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
            if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
                openssl_free_key($res);
            }
            $sign = base64_encode($sign);
            return $sign;
        }
        public function getSignContent($params) {
            ksort($params);
            $stringToBeSigned = "";
            $i = 0;
            foreach ($params as $k => $v) {
                if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                    // 转换成目标字符集
                    $v = $this->characet($v, 'UTF-8');
                    if ($i == 0) {
                        $stringToBeSigned .= "$k" . "=" . "$v";
                    } else {
                        $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                    }
                    $i++;
                }
            }
            unset ($k, $v);
            return $stringToBeSigned;
        }
        protected function checkEmpty($value) {
            if (!isset($value))
                return true;
            if ($value === null)
                return true;
            if (trim($value) === "")
                return true;
            return false;
        }
        /**
         * 转换字符集编码
         * @param $data
         * @param $targetCharset
         * @return string
         */
        function characet($data, $targetCharset) {
            if (!empty($data)) {
                $fileType = 'UTF-8';
                if (strcasecmp($fileType, $targetCharset) != 0) {
                    $data = mb_convert_encoding($data, $targetCharset, $fileType);
                }
            }
            return $data;
        }
        /**
         * 支付宝同步通知回调
         */
        public function aliReturn()
        {
            header('Refresh:2;url=/order/list');
            echo "订单： ".$_GET['out_trade_no'] . ' 支付成功，正在跳转';
    //        echo '<pre>';print_r($_GET);echo '</pre>';die;
    //        //验签 支付宝的公钥
    //        if(!$this->verify($_GET)){
    //            die('簽名失敗');
    //        }
    //
    //        //验证交易状态
    ////        if($_GET['']){
    ////
    ////        }
    ////
    //
    //        //处理订单逻辑
    //        $this->dealOrder($_GET);
        }
        /**
         * 支付宝异步通知
         */
        public function aliNotify()
        {
            $data = json_encode($_POST);
            $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
            //记录日志
            file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            //验签
            $res = $this->verify($_POST);
            $log_str = '>>>> ' . date('Y-m-d H:i:s');
            if($res === false){
                //记录日志 验签失败
                $log_str .= " Sign Failed!<<<<< \n\n";
                file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            }else{
                $log_str .= " Sign OK!<<<<< \n\n";
                file_put_contents('logs/alipay.log',$log_str,FILE_APPEND);
            }
            //验证订单交易状态
            if($_POST['trade_status']=='TRADE_SUCCESS'){
                //更新订单状态
                $oid = $_POST['out_trade_no'];     //商户订单号
                $info = [
                    'is_pay'        => 1,       //支付状态  0未支付 1已支付
                    'pay_amount'    => $_POST['total_amount'] * 100,    //支付金额
                    'pay_time'      => strtotime($_POST['gmt_payment']), //支付时间
                    'plat_oid'      => $_POST['trade_no'],      //支付宝订单号
                    'plat'          => 1,      //平台编号 1支付宝 2微信 
                ];
                OrderModel::where(['oid'=>$oid])->update($info);
            }
            //处理订单逻辑
            $this->dealOrder($_POST);
            echo 'success';
        }
        //验签
        function verify($params) {
            $sign = $params['sign'];
            $params['sign_type'] = null;
            $params['sign'] = null;
    
            if($this->checkEmpty($this->aliPubKey)){
                $pubKey= $this->publicKey;
                $res = "-----BEGIN PUBLIC KEY-----\n" .
                    wordwrap($pubKey, 64, "\n", true) .
                    "\n-----END PUBLIC KEY-----";
            }else {
                //读取公钥文件
                $pubKey = file_get_contents($this->aliPubKey);
                //转换为openssl格式密钥
                $res = openssl_get_publickey($pubKey);
            }
            
           
            
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
            ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
            //调用openssl内置方法验签，返回bool值
            $result = (openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256)===1);
            openssl_free_key($res);
            return $result;
        }
        /**
         * 处理订单逻辑 更新订单 支付状态 更新订单支付金额 支付时间
         * @param $data
         */
        public function dealOrder($data)
        {
            //加积分
            //减库存
        }
    }
    

