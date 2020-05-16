<?php

namespace app\admin\controller;

use app\admin\model\Progress as modelProgress;
use app\admin\model\User as modelUser;
use app\admin\model\Exam as modelExam;
use app\admin\model\examStatus as modelExamStatus;
use app\admin\model\Question as modelQuestion;
use think\Session;

class Api extends \think\Controller
{

    public function __construct()
    {
        parent::__construct();
        config('default_return_type', 'json',);     #非跨域API使用json
        // config('default_return_type', 'jsonp',); #跨域API使用jsonp
    }

    //展示信息
    /**
     * 返回所有学生的学习进度,按json返回,返回一个列表，每个元素包含uid,登录名，真实姓名，学习进度
     * 为了方便，不设权限，直接访问/admin/getAllProgress即可看到结果
     */
    public function getAllProgress()
    {
        $iProgress = new modelProgress();
        $res = ($iProgress->where('uid', '>', 1)->select());
        return [
            'status' => true,
            'data'  => $res
        ];
    }

    /**
     * 返回所有学生的学习时长,按json返回,返回一个列表，每个元素包含uid,登录名，真实姓名，累计学习时长
     * 为了方便，不设权限，直接访问/admin/getStudyTime即可看到结果
     */
    public function getStudyTime()
    {
        $iUser = new modelUser();
        $res = ($iUser->where('uid', '>', 1)->select());
        foreach ($res as $tUser) {
            unset($tUser->password);
        }
        return [
            'status' => true,
            'data'  => $res
        ];
    }

    //学生信息
    /**
     * 返回所有学生的学生信息,按json返回,返回一个列表，每个元素包含uid,登录名，真实姓名，上次在线时间(时间戳)
     * 展示的时候密码要占位（以方便修改，但显示的值可以空着或者直接*******）
     * 为了方便，不设权限，直接访问/admin/getAllStudentInfo即可看到结果
     */
    public function getAllStudentInfo()
    {
        $iUser = new modelUser();
        $res = $iUser->where('uid', '>', 1)->select();
        foreach ($res as $tUser) {
            unset($tUser->password);
            unset($tUser->lastUpdate);
        }
        return [
            'status' => true,
            'data'  => $res
        ];
    }

    /**
     * 提供3个字段,uid,需要修改的字段,需要修改的值
     * post uid column value
     * password字段会自动转换为加密存储
     * /admin/updateStudentInfo
     */
    public function updateStudentInfo()
    {
        if (empty($_POST['uid']) || empty($_POST['column']) || empty($_POST['value'])) {
            return [
                'status' => false,
                'data'  => 'uid、column或value未提供'
            ];
        }
        if ($_POST['column'] === 'password') {
            $_POST['value'] = password_hash($_POST['value'], PASSWORD_DEFAULT);
        }
        $currentUserModel = new modelUser();
        if ($currentUserModel->save([$_POST['column'] => $_POST['value']], ['uid' => $_POST['uid']])) {
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

    /**
     * 通过uid删除学生，需要提供一个uid参数
     */
    public function deleteStudent()
    {
        if (empty($_POST['uid'])) {
            return [
                'status' => false,
                'data'  => 'uid未提供'
            ];
        }
        $iUser = new modelUser();
        $iProgress = new modelProgress();
        $iExam = new modelExam();
        $iExamStatus = new modelExamStatus();
        if (
            $iUser->where('uid', $_POST['uid'])->delete()
            && $iProgress->where('uid', $_POST['uid'])->delete()
            && $iExam->where('uid', $_POST['uid'])->delete()
        ) {
            $iExamStatus->where('uid', $_POST['uid'])->delete();
            return [
                'status' => true,
                'data'  => 'succeeded'
            ];
        } else {
            return [
                'status' => false,
                'data'  => '数据库错误'
            ];
        }
    }

    //选择题控制

    /**
     * 提供6个字段,题面,正确答案,错误答案A,错误答案B,错误答案C,题目解析
     * question	correctRes	fakeA	fakeB	fakeC	resDetail	
     * 成功的话会返回qid字段
     * /admin/addQuestion
     */
    public function addQuestion()
    {
        $iQuestion = new modelQuestion($_POST);
        if ($iQuestion->save()) {
            return [
                'status' => true,
                'qid'  => $iQuestion->qid
            ];
        } else {
            return [
                'status' => false,
                'data'  => '数据库错误'
            ];
        }
    }

    /**
     * 无需字段
     * 成功的话data字段返回数据,否则返回 “数据库错误”
     * /admin/getAllQuestion
     */
    public function getAllQuestion()
    {
        $iQuestion = new modelQuestion();
        return [
            'status' => true,
            'data'  => $iQuestion->select()
        ];
    }

    /**
     * 需要一个qid字段
     * 成功的话data字段返回OK,否则返回 “删除失败”
     * /admin/deleteQuestion
     */
    public function deleteQuestion()
    {
        $iQuestion = new modelQuestion($_POST);
        $iExamStatus = new modelExamStatus();
        if ($iQuestion->where('qid', $_POST['qid'])->delete()) {
            $iExamStatus->where('qid', $_POST['qid'])->delete();
            return [
                'status' => true,
                'data'  => 'OK'
            ];
        } else {
            return [
                'status' => false,
                'data'  => '删除失败'
            ];
        }
    }

    /**
     * 提供3个字段,qid,需要修改的字段,需要修改的值
     * post qid column value
     */
    public function updateQuestion()
    {
        $iQuestion = new modelQuestion();
        if ($iQuestion->save([$_POST['column'] => $_POST['value']], ['qid' => $_POST['qid']])) {
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

    //学习成绩管理

    /**
     * 无需字段
     * 成功的话data字段返回数据,否则返回 “数据库错误”
     * 返回数据分别为 用户id,最高成绩,上一次的成绩
     * /admin/getAllExamScore
     */
    public function getAllExamScore()
    {
        $iExam = new modelExam();
        $res = $iExam->select();
        return [
            'status' => true,
            'data'  => $res
        ];
    }

    //选择题学生端接口,利用session
    /**
     * 无需查询字段
     * 返回数据可以自行观察接口返回值
     * /api/getAnExam
     */
    public function getAnExam()
    {
        Session::prefix('exam');
        session("started", true);
        $iQuestion = new modelQuestion();
        $allQuestions = $iQuestion->select();
        $selectedQuestions = [];
        $returnData = [];
        array_push($selectedQuestions, rand(0, count($allQuestions) - 1));
        while (count($selectedQuestions) < 5) {
            if (!in_array($tmp = rand(0, count($allQuestions) - 1), $selectedQuestions)) {
                array_push($selectedQuestions, $tmp);
            }
        }
        for ($i = 0; $i <= 4; $i++) {
            $selectedQuestions[$i] = $allQuestions[$selectedQuestions[$i]];
            session('question_' . $i . '_qid', $selectedQuestions[$i]->qid);
            $question = ['question' => $selectedQuestions[$i]->question];
            //打乱正确答案
            $correctChoiceNum = rand(0, 3);
            $correctChoice = chr(ord('A') + $correctChoiceNum);
            session('question_' . $i . '_correctChoice', $correctChoice);
            //填充错误答案
            $t = 0;
            for ($j = 0; $j < 4; $j++) {
                if ($j === $correctChoiceNum) {
                    $question[chr(ord('A') + $j)] = $selectedQuestions[$i]->correctRes;
                } else {
                    $question[chr(ord('A') + $j)] = $selectedQuestions[$i]->getAttr("fake" . chr(ord('A') + $t++));
                }
            }
            array_push($returnData, $question);
        }
        return $returnData;
    }
    /**
     * 查询字段:
     * 0为第一题的答案,1第二题的答案,以此类推,答案值为A、B、C、D字母即可
     * 返回数据:
     * score:总分
     * answer数组,值为bool,即对错
     * /api/judgeExam
     */
    public function judgeExam()
    {
        $uid = session("uid");
        Session::prefix('exam');
        if (!session("started")) {
            return false;
        } else {
            session("started", false);
        }
        $score = 0;
        for ($i = 0; $i < 5; $i++) {
            $res[$i] = session('question_' . $i . '_correctChoice') === $_POST[$i];
            //
            $mES = new modelExamStatus();
            if ($SQLres = $mES->where('uid', $uid)
                ->where('qid', session('question_' . $i . '_qid'))
                ->find()
            ) {
                //修改
                if (session('question_' . $i . '_correctChoice') != $_POST[$i]) {
                    $SQLres->qcondition = 1;
                    $SQLres->save();
                }
                //结束修改
            } else {
                //新增
                $mES = new modelExamStatus();
                $mES->qcondition = session('question_' . $i . '_correctChoice') != $_POST[$i] ? 1 : 0;
                $mES->qid = session('question_' . $i . '_qid');
                $mES->uid = $uid;
                $mES->save();
                //结束新增
            }
            if ($res[$i]) $score++;
        }
        $response['score'] = $score / 5 * 100;
        $uEM = new modelExam();
        $uEM = $uEM->get($uid);
        $uEM->lastScore = $response['score'];
        if ($uEM->maxScore < $response['score']) {
            $uEM->maxScore = $response['score'];
        }
        $uEM->save();
        $response['answer'] = $res;
        $iQuestion = new modelQuestion();
        for ($i = 0; $i < 5; $i++) {
            $response['resDetail'][$i] = $iQuestion->get(session('question_' . $i . '_qid'))["resDetail"];
        }
        return $response;
    }
    /**
     * 无需查询字段
     * 返回本人错题数据,格式观察返回值即可:
     * /api/getMyWrongTitleSet
     */
    public function getMyWrongTitleSet()
    { //获取错题集
        $mES = new modelExamStatus();
        $SQLres = $mES->where('uid', session('uid'))->order('qid')->column('qid');
        $res = array();
        foreach ($SQLres as $qid) {
            $mQ = new modelQuestion();
            $SQLres = $mQ->get($qid);
            unset($SQLres["fakeA"]);
            unset($SQLres["fakeB"]);
            unset($SQLres["fakeC"]);
            array_push($res, $SQLres);
        }
        return ($res);
    }
}
