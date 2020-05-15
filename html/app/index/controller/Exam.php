<?php

namespace app\index\controller;

use app\index\model\Question as modelQuestion;
use app\index\model\Exam as modelExam;

class Exam extends \think\Controller
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

    public function getQuestions($count) #获取题目
    {
        # code...
    }

    public function finishExam() #上交题目并返回结果(评分、正误、解析)
    {
        # code...
    }

    public function getMyFailed() #返回做错过的题目
    {
        # code...
    }

    public function getMyFavorite() #返回收藏的题目
    {
        # code...
    }

}
