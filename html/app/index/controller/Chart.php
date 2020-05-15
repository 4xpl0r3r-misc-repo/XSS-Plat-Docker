<?php

namespace app\index\controller;

use app\index\model\Progress as modelProgress;

class Chart extends \think\Controller
{

    // if ($res = $this->checkLogon()) return $res; //检查登录状态
    protected function checkLogon()
    {
        if (empty(session('uid'))) {
            return [
                'staus' => false,
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

    public function getPeronalProgressData() //个人学习进度表
    {
        $currentUserModel=new  modelProgress();
        
    }

}
