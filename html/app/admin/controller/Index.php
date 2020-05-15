<?php

namespace app\admin\controller;

class Index extends \think\Controller
{

    public function index()
    {
        $page=substr($_SERVER['PATH_INFO'],1);
        if (session('uid')===1) {//uid=1时为超级管理员
            $this->assign('username', '管理员');
            $this->assign('realName', '管理员');
        } else {
            header("Location: /login/");
            exit;
        }
        $this->assign('page', $page);
        return view($page);
    }
}
