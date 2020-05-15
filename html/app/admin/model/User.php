<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $table = 'userInfo';
    protected $pk = 'uid';

    public function checkUsedUsername()
    {
        if (!empty($this->getAttr('username'))  && $this->where('username', $this->getAttr('username'))->find()) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUsedRealName()
    {
        if (!empty($this->getAttr('realName')) && $this->where('realName', $this->getAttr('realName'))->find()) {
            return true;
        } else {
            return false;
        }
    }
}
