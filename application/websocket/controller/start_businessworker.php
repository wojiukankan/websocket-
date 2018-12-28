<?php
/**
 * Created by IntelliJ IDEA.
 * User: Jenny
 * Date: 2018/6/22
 * Time: 17:49
 */
use \Workerman\Worker;
use \GatewayWorker\BusinessWorker;

require_once __DIR__ . '/../../../vendor/autoload.php';
$worker = new BusinessWorker();
$worker->name = 'ChatBusinessWorker';
$worker->count = 1;
$worker->registerAddress = '127.0.0.1:1238';

/*
 * 设置处理业务的类为MyEvent。
 * 如果类带有命名空间，则需要把命名空间加上，
 * 类似$worker->eventHandler='\my\namespace\MyEvent';
 */
$worker->eventHandler = 'Events';

if(!defined('GLOBAL_START')){
    Worker::runAll();
}