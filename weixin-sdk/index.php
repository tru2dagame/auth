<?php

include_once ('../config.php');
include_once (WEIXIN_PATH . '/class/wechat.class.php');

$options = array(
        'token' => WECHAT_TOKEN,
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
        if ($content == "我要上网") {
            $weObj->text("<a href='" . SERVER_HOST . "/guest/weixin-sdk/redirct.php?fromUserName=" . $fromUserName . "'>点击上网</a>")->reply();
        }
        break;
    case Wechat::MSGTYPE_EVENT:
        if ($event == 'subscribe') {  //关注微信操作
                $weObj->text("您好，欢迎关注UBNT！Ubiquiti Networks公司在全球范围内设计，制造和销售创新性的宽带无线解决方案。UBNT的产品包括具有颠覆意义的无线产品，如Bullet（TM），NanoStation（TM），以及其他结合基于内部开发、业界领先的产品设计核心技术，包括Air OS操作系统和频率自由技术（100MHz至10GHz的RF设计）。访问UBNT官方主页www.ubnt.com.cn 官方论坛bbs.ubnt.com.cn 官方商城store.ubnt.com.cn了解更多。")->reply();
        } else if ($event == 'unsubscribe') {  //取消关注微信操作
            //取消上网权限
            $sql = "select * from " . DB_TABLE . " WHERE `fromUserName` = '{$fromUserName}'";
            $res = $mysql->query($sql, 'all');

            if (is_array($res) && count($res) > 0) {
                //删除数据
                $sql = "DELETE FROM " . DB_TABLE . " WHERE `fromUserName` = '{$fromUserName}'";
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