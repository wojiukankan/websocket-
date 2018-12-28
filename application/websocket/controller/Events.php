<?php

use \GatewayWorker\Lib\Gateway;

class Events {

    public static $apiUrl = 'http://tp5.com/index/';

    // 当有客户端连接时
    public static function onConnect($client_id) {

    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param string $message 具体消息
     */
    public static function onMessage($client_id, $param) {
        $data = json_decode($param, true);
        if ($data['action'] == 'replyWelcome') {//握手
            Gateway::bindUid($client_id, $data['openid']);
            $json = array('code' => 1, 'msg' => 'welcome', 'type' => $data['action'], 'data' => array());
            Gateway::sendToClient($client_id, json_encode($json));
        } else {
            $url = Events::$apiUrl . $data['controller'] . DIRECTORY_SEPARATOR . $data['action'];
            $json = Events::curl_post($url,$data);
            $data = json_decode($json,true);
            $message = json_encode($data['data']);
            switch ($data['sendObj']){
                case 'myself':
                    Gateway::sendToClient($client_id, $message);
                case 'allObj':
                    Gateway::sendToAll($message);
                case 'openidObj':
                    foreach ($data['openidArr'] as $item){
                        Gateway::sendToUid($item,$message);
                    }
            }

        }
    }
    /**
     * 编辑返回的json数据格式
     * 时间： 2017-11-15 下午04:01:07
     * @author: gaosheren 861216024@qq.com
     * @param string $sendObj
     * myself  自己
     * noObj  不返回信息
     * allObj和空  所有用户
     * openidObj 绑定的openid用户，需要$rel_info中有传递参数openidArr
     * @param string $message 发送内容
     * @param array openidArr 广播对象为openidObj时，要广播的openid数组array('openid','openid');
     * @param array $timerInfo 有定时任务时的定时信息（空就不调用定时器）array('countdownTime'=>'定时时间')
     */
    protected function _ajaxReturn($message,$sendObj='allObj',$openidArr=array(),$timerInfo=array()){
        echo json_encode(array('sendObj'=>$sendObj,'message'=>$message,'openidArr'=>$openidArr,'timerInfo'=>$timerInfo));
        exit;
    }
    //curl请求
    private static function curl_post($url, $sParams) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}