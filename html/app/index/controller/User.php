<?php

namespace app\index\controller;

use app\index\model\User as modelUser;
use app\index\model\Progress as modelProgress;
use app\index\model\Exam as modelExam;

class User extends \think\Controller
{

    protected function checkUsernameValidate($currentUserArray)
    {
        if (empty($currentUserArray['username'])) {
            return '缺少用户名字段';
        }
        $username = $currentUserArray['username'];
        if (mb_strlen($username) > 20) {
            return '用户名太长，最长为20个字符';
        }
        if (mb_strlen($username) < 2) {
            return '用户名太短，最短为2个字符';
        }
        if (preg_match("/[^\w]/", $username) !== 0) {
            return '用户名必须为字母、数字或下划线';
        }
        return false; //无异常
    }

    // protected function checkEmailValidate($currentUserArray)
    // {
    //     if (empty($currentUserArray['email'])) {
    //         return 'email not found';
    //     }
    //     $email = $currentUserArray['email'];
    //     if (mb_strlen($email) > 255) {
    //         return 'email too long,maximum is 255';
    //     }
    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         return 'email address is invalidate';
    //     }
    //     return false; //无异常
    // }

    protected function checkPasswordStrength($currentUserArray)
    {
        if (empty($currentUserArray['password'])) {
            return '缺少密码字段';
        }
        $password = $currentUserArray['password'];
        if (mb_strlen($password) < 8) {
            return '密码太短，最小为8';
        }
        return false; //无异常
    }

    protected function checkRegisterInfo($currentUserArray)
    {
        if (
            empty($currentUserArray['username']) ||
            empty($currentUserArray['realName']) ||
            empty($currentUserArray['password'])
        ) {
            return "缺少必要字段";
        }
        $currentUserModel = new modelUser($currentUserArray);
        if ($res = $this->checkUsernameValidate($currentUserArray)) {
            return $res;
        } else if ($res = $this->checkPasswordStrength($currentUserArray)) {
            return $res;
        } else if ($currentUserModel->checkUsedRealName()) {
            return "姓名已被占用，请联系老师";
        } else if ($currentUserModel->checkUsedUsername()) {
            return "用户名已被占用，请更换用户名";
        }
    }

    protected function checkUpdateInfo($currentUserArray) //只能修改密码
    {
        if (
            empty($currentUserArray['password'])
        ) {
            return "密码字段为空";
        }
        $currentUserModel = new modelUser();
        if (
            !empty($currentUserArray['password'])
            && ($res = $this->checkPasswordStrength($currentUserArray))
        ) {
            return $res;
        }
        return false;
    }

    public function __construct()
    {
        parent::__construct();
        config('default_return_type', 'json',);     #非跨域API使用json
        // config('default_return_type', 'jsonp',); #跨域API使用jsonp
    }

    public function register()
    {
        if ($res = $this->checkRegisterInfo($_POST)) {
            return [
                'status'    => false, //failed
                'message'   => $res
            ];
        } else {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $currentUserModel = new modelUser($_POST); //username,password,realName
            if (!$currentUserModel->allowField(true)->isUpdate(false)->save()) {
                $currentUserModel->delete();
                return [
                    'status'    => false,
                    'message'   => '数据库错误,错误存在于userInfo表'
                ];
            }
            $createProgress = new modelProgress();
            $createProgress->uid = $currentUserModel->uid;
            if (!$createProgress->isUpdate(false)->save()) { // 过滤post数组中的非数据表字段数据
                $currentUserModel->delete();
                return [
                    'status'    => false,
                    'message'   => '数据库错误,错误存在于userProgress表'
                ];
            }
            $createExamStat = new modelExam();
            $createExamStat->uid = $currentUserModel->uid;
            if ($createExamStat->isUpdate(false)->save()) { // 过滤post数组中的非数据表字段数据
                return [
                    'status'    => true,
                    'message'   => 'OK'
                ];
            } else {
                $createProgress->delete();
                $currentUserModel->delete();
                return [
                    'status'    => false,
                    'message'   => '数据库错误,错误存在于examStatus表'
                ];
            }
        }
    }
    public function login()
    {
        $currentUserModel = new modelUser($_POST); //user,password
        if (empty($currentUserModel->user) || empty($currentUserModel->password)) {
            return [
                'status'    => false, //failed
                'message'   => '缺少必要的数据字段'
            ];
        }
        // if (filter_var($currentUserModel->user, FILTER_VALIDATE_EMAIL)) { //邮件地址登录
        //     $loginType = 'email';
        // } else { //用户名登录
        $loginType = 'username';
        // }
        if (($res = $currentUserModel->where($loginType, $currentUserModel->user)->find()) &&
            password_verify($currentUserModel->password, $res["password"])
        ) {
            session('uid', $res['uid']);
            session('username', $res['username']);
            session('realName', $res['realName']);
            if ($res['uid'] === 1) {
                return [
                    'status'    => true,
                    'message'   => 'Admin'
                ];
            } else {
                return [
                    'status'    => true,
                    'message'   => 'OK'
                ];
            }
        } else {
            return [
                'status'    => false,
                'message'   => '用户名或密码错误'
            ];
        }
    }
    public function update()
    {
        if (!session('uid')) {
            return [
                'status'    => false,
                'message'   => '未登录'
            ];
        }
        if ($res = $this->checkUpdateInfo($_POST)) {
            return [
                'status'    => false,
                'message'   => $res
            ];
        }
        if (empty($_POST['password'])) {
            unset($_POST['password']);
        } else {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        if (empty($_POST['realName'])) {
            unset($_POST['realName']);
        }
        $currentUserModel = new modelUser();
        if ($currentUserModel->allowField(['password'])->save($_POST, ['uid' => session('uid')])) {
            return [
                'status'    => true,
                'message'   => 'OK'
            ];
        } else {
            return [
                'status'    => false,
                'message'   => '数据库错误'
            ];
        }
    }

    public function logout()
    {
        session_destroy();
        return true;
    }
}
