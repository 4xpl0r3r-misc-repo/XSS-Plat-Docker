<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '[user]'  => [
        'register'  => 'index/User/register',
        'login'     => 'index/User/login',
        'update'    => 'index/User/update',
        'logout'    => 'index/User/logout'
    ],
    '[progress]'  => [
        'getCheckpointData' => 'index/Progress/getCheckpointData',
        'setCheckpoint' => 'index/Progress/setCheckpoint',
    ],
    '[virtualenv]'  => [
        'start' => 'index/Virtualenv/start',
        'shutdown' => 'index/Virtualenv/shutdown',
        'recover' => 'index/Virtualenv/recover',
    ],
    '[time]'  => [
        'getTotalTime' => 'index/Time/getTotalTime',
        'addTime' => 'index/Time/addTime',
    ],
    //admin
    'progressManage'         => 'admin/Index/index',
    'scores'         => 'admin/Index/index',
    'infoManage'         => 'admin/Index/index',
    'QuestionManage'         => 'admin/Index/index',
    '[admin]'  => [
        'getAllProgress'    => 'admin/Api/getAllProgress',
        'getStudyTime'      => 'admin/Api/getStudyTime',
        'getAllExamScore'      => 'admin/Api/getAllExamScore',

        'getAllStudentInfo'      => 'admin/Api/getAllStudentInfo',
        'updateStudentInfo'      => 'admin/Api/updateStudentInfo',
        'deleteStudent'      => 'admin/Api/deleteStudent',
        
        'getAllQuestion'     => 'admin/Api/getAllQuestion',
        'deleteQuestion'         => 'admin/Api/deleteQuestion',
        'updateQuestion'      => 'admin/Api/updateQuestion',
        'addQuestion'      => 'admin/Api/addQuestion',
    ],
    '[api]'  => [
        'getAnExam'         => 'admin/Api/getAnExam',
        'judgeExam'         => 'admin/Api/judgeExam',
        'getMyWrongTitleSet'=> 'admin/Api/getMyWrongTitleSet',
    ],
    ''              => 'index/Index/index',
    ':page$'         => 'index/Index/index', //通配
];
