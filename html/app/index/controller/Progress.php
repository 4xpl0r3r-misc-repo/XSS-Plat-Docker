<?php

namespace app\index\controller;

use app\index\model\Progress as modelProgress;

class Progress extends \think\Controller
{

    public function __construct()
    {
        parent::__construct();
        config('default_return_type', 'json',);     #非跨域API使用json
        // config('default_return_type', 'jsonp',); #跨域API使用jsonp
    }

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

    public function getCheckpointData()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态

        $currentUserModel = new modelProgress();
        if ($res = ($currentUserModel->where('uid', session('uid'))->find())) {
            unset($res['uid']);
            return [
                'status' => true,
                'data'  => $res
            ];
        } else {
            return [
                'status' => false,
                'data'  => 'unkown SQL exception'
            ];
        }
    }

    public function setCheckpoint()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        //date("Y-m-d")
        $currentUserModel = new modelProgress();

        foreach ($_POST as &$t) {
            if($t==="true"){
                $t = date("Y-m-d");
            }else{
                $t = NULL;
            }
        }
        unset($t);

        if ($currentUserModel->allowField(true)->save($_POST, ['uid' => session('uid')])) {
            return [
                'status'    => true,
                'message'   => 'OK'
            ];
        } else {
            return [
                'status'    => true,
                'message'   => '数据过频,无效'
            ];
        }
    }
}
