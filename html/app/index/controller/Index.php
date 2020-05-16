<?php

namespace app\index\controller;

class Index extends \think\Controller
{

    public function index($page='')
    {
        if($page==''){
            header("Location: /intro-platform");
            exit;
        }
        if (session('?uid')) {
            // $this->assign('avatar', 'https://cdn.v2ex.com/gravatar/' . session('avatar') . '?s=128&d=identicon');
            $this->assign('username', session('username'));
            $this->assign('realName', session('realName'));
        } else {
            header("Location: /login/");
            exit;
        }
        if (session('uid')===1) {
            $this->assign('isAdmin', True);
        }else{
            $this->assign('isAdmin', False);
        }
        $this->assign('page', $page);
        if (strpos("$page","-")!==false) {
            return view("index/".implode ("/",explode("-",$page)));
        }else{
            return view($page);
        }
    }
}
