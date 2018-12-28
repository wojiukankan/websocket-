<?php
/**
 * Created by PhpStorm.
 * User: lzhd
 * Date: 2018/12/28
 * Time: 14:05
 */

namespace app\index\controller;


use think\Controller;

class Index extends Controller {

    public function index() {
        return $this->fetch();
    }
    public function cccaa(){

        return json(array('data'=>array(123),'sendObj'=>'allObj','openidArr'=>array(1)));
    }
}