<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LoginController extends Controller
{
    public function login()
    {
        return view('login.login');
    }

    public function add()
    {
        $name = request()->name;
        $u_pwd = request()->pwd;
        $pwd= md5($u_pwd);
        $res = DB::table('login')->where('name',$name)->where('pwd',$pwd)->first();
        if($res){
            session(['login_id'=>$res->login_id]);
            return (['code'=>1]);
        }else{
            return (['code'=>2]);
        }
    }

    public function reg()
    {
        $redis = new \Redis();
        $redis -> connect('127.0.0.1','6379');
        $redis->incr('num');
        $num = $redis->get('num');
        echo $num;
        return view('login.reg');
    }

    public function cate()
    {
       $data = request()->all();
       $data['pwd']=md5($data['pwd']);
        $res = DB::table('login')->insert($data);
        if($res){
            return (['code'=>1]);
        }else{
            return (['code'=>2]);
        }
    }

    public function check()
    {
        $name = request()->name;
        if(strpos($name,'@') !=false){
            //youxiang
            $code=rand(1000,9999);
            $res=$this->sendMail1($name,$code);
            if(!$res){   
                session(['name'=>$name,'code'=>$code]);
                  
                return ['code'=>1, 'res'=>'邮件发送成功'];
            }else{
                return ['code'=>0, 'res'=>'邮件发送失败'];
            }
        }
    }

    public function sendMail1($name,$code)
        {
            \Mail::raw('你的验证码为'."$code".'哈哈哈',function ($message)use($name){
                $message->subject("...");
                $message->to($name);
            });
        }

    public function checkcode()
    {
        $name = request()->name;
        $code = request()->code;
        if(!empty($name) || !empty($code)){
            $u_name = session('name');
            $u_code = session('code');
            if($code != $u_code || $name !=$u_name){
                return ['code'=>1,'res'=>'验证码或邮箱不正确'];
            }
        }
    }

    public function checkname()
    {
        $name = request()->name;
        if($name){
            $where['name']=$name;
            $count = DB::table('login')->where($where)->count();
            return ['code'=>1,'count'=>$count];
        }
    }
}
