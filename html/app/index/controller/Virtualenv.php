<?php

namespace app\index\controller;

use think\Db;

class Virtualenv extends \think\Controller
{
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
        config('database.database', 'xss-platform');
        Db::connect();
        $this->cuCp = $_POST['Cp'];
        $this->tableName = $this->cuCp . '_' . session('username');
    }

    public function start()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        if ($this->cuCp === '2_2') {
            $SQL = <<<sql
            CREATE TABLE `xss-platform`.`$this->tableName` ( `comment` TEXT NOT NULL ) ENGINE = InnoDB charset utf8mb4;
            sql;
            $res = Db::query($SQL);
            $SQL = <<<sql
            INSERT INTO `$this->tableName` (`comment`) VALUES ('我是老王，住你隔壁，喜欢绿色'), ('我是小明，住你楼上，喜欢搞黄色');
            sql;
            $res = Db::query($SQL);
        }
        return [
            'status' => true,
        ];
    }

    public function shutdown()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        if ($this->cuCp === '2_2') {
            $SQL = <<<sql
            DROP TABLE `$this->tableName`;
            sql;
            $res = Db::query($SQL);
        }
        return [
            'status' => true,
        ];
    }

    public function recover()
    {
        if ($res = $this->checkLogon()) return $res; //检查登录状态
        try{
            $this->shutdown();
        }catch(\Exception $e){}
        if ($this->start()['status']) return [
            'status' => true,
        ];;
    }
}
