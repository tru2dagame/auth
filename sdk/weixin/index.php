<?php

include_once ('../../config.php');
include_once (WEIXIN_PATH . '/class/wechat.class.php');

$options = array(
    'token' => WECHAT_TOKEN,
    'logcallback' => 'logdebug',
    );
$weObj = new Wechat($options);
$weObj->valid();

$rev = $weObj->getRev();
$type = $rev->getRevType();
$revEvent = $rev->getRevEvent();
$event = $revEvent['event'];
$fromUserName = $rev->getRevFrom();
switch($type) {
    case Wechat::MSGTYPE_TEXT:
        $content = $rev->getRevContent();
        if ($content == WEIXIN_AUTH_MESSAGE) {
            $text = "<a href='" . SERVER_HOST . "/guest/sdk/weixin/redirct.php?fromUserName=" . $fromUserName . "'>点击上网</a>";
            $weObj->text($text)->reply();
        }
        break;
    case Wechat::MSGTYPE_EVENT:
        if ($event == 'subscribe') {  //关注微信操作
                $weObj->text(WEIXIN_ADD_WELCOME_MESSAGE)->reply();
        } else if ($event == 'unsubscribe') {  //取消关注微信操作
            //取消上网权限
            $sql = "select * from " . WEIXIN_TABLE . " WHERE `fromUserName` = '{$fromUserName}'";
            $res = $mysql->query($sql, 'all');

            if (is_array($res) && count($res) > 0) {
                //删除数据
                $sql = "DELETE FROM " . WEIXIN_TABLE . " WHERE `fromUserName` = '{$fromUserName}'";
                $mysql->query($sql);
            
                foreach ($res as $key => $value) {
                    UniFi::sendUnauthorization($value['Mac_ID']);
                    sleep(5);
                }
            }
        }
        break;
    case Wechat::MSGTYPE_IMAGE:

        break;
    default:
            $weObj->text("help info")->reply();
        break;
}

function logdebug($text){
    file_put_contents('../data/log.txt',$text."\n",FILE_APPEND);
};
$weObj->debug = true;
//获取菜单操作:
$menu = $weObj->getMenu();
//设置菜单
$newmenu =  array(
    "button"=> array(
        array('sub_button', 'name'=>'产品介绍', array(
            array('type'=>'view', 'name'=>'airMAX系列','url'=>'http://www.ubnt.com.cn/broadband/'),
            array('type'=>'view', 'name'=>'UniFi系列','url'=>'http://www.ubnt.com.cn/enterprise/'),
            array('type'=>'view', 'name'=>'其他产品','url'=>'http://www.ubnt.com.cn/products/'),
        )),
        array('type'=>'sub_button', 'name'=>'如何购买', array(
            array('type'=>'view', 'name'=>'直接购买','url'=>'https://store.ubnt.com.cn/'),
            array('type'=>'view', 'name'=>'寻找分销商','url'=>'http://www.ubnt.com.cn/distributors/'),
        )),
        array('type'=>'view', 'name'=>'联系我们','url'=>'http://www.ubnt.com.cn/contact/'),
        )
    );
$result = $weObj->createMenu($newmenu);