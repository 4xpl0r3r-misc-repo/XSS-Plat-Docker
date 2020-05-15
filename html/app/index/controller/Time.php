<?php

namespace app\index\controller;

use app\index\model\User as modelUser;

class Time extends \think\Controller
{

    // if ($res = $this->checkLogon()) return $res; //检查登录状态
    protected function checkLogon()
    {
        if (empty(session('uid'))) {
            return [
                'status' => false,
                'data'  => 'uid in session not found'
            ];
        }
        return false;
    }

    public function __construct()
    {
        parent::__construct();
        config('default_return_type', 'json',);     #非跨域API使用json
        // config('default_return_type', 'jsonp',); #跨域API使用jsonp
    }

    public function getTotalTime()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        $user = modelUser::get(session("uid"));
        return [
            'status' => true,
            'data'  => $user->totalTime
        ];
    }
    public function addTime()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        $user = modelUser::get(session("uid"));
        if ($user->lastUpdate&&time()-$user->lastUpdate<4) {
            return false;
        }
        $user->lastUpdate=time();
        $user->totalTime+=5;
        $user->save();
        return true;
    }
}
