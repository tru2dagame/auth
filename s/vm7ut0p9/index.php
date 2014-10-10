<?php
error_reporting(E_ALL);
include_once ('../../config.php');

$array_tmp = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
$site = end($array_tmp);
$site = is_string($site) ? $site : 'default';

$Mac_ID = isset($_GET['id']) ? addslashes($_GET['id']) : '';
$fromUserName = isset($_GET['fromUserName']) ? addslashes($_GET['fromUserName']) : '';


$sql = "select `Mac_ID` from " . WEIXIN_TABLE . " where `Mac_ID` = '{$Mac_ID}' and `ticket` != ''";
$res = $mysql->query($sql, '1');

if ($res) {
    include_once (DEPS_PATH . '/weixin_success.php');
}

if (!$Mac_ID) {
    header('Location: template/introduce.html');
    exit();
}

$sql = "select * from " . WEIXIN_TABLE . " where `Mac_ID` = '{$Mac_ID}' and `ticket` = 'authorized'";
$res = $mysql->query($sql, 'all');

if (!is_array($res) || count($res) <= 0) {
    if (!$fromUserName) {
        include_once (WEIXIN_PATH . '/class/wechat.class.php');
        $options = array(
                         'token'=>'UBNTXjNqxjty', //填写你设定的key
                         // 'appid'=>'wx1192836da9513680', //填写高级调用功能的app id
                         'appid'=>'wxcb06cb78eeb366c2', //填写高级调用功能的app id
                         // 'appsecret'=>'795fe5cd06e4b50e7c1b438df4eb0cd7', //填写高级调用功能的密钥
                         'appsecret'=>'df459a7c980c99ecccb50047a1de813a', //填写高级调用功能的密钥
                         );
        
        $weObj = new Wechat($options);

        $sql = "select `Mac_ID`, `scene_id` from " . WEIXIN_TABLE . " where `Mac_ID` = '{$Mac_ID}' and `ticket` != 'authorized' limit 1";
        $result = $mysql->query($sql, 'all');
        if (!is_array($result) || count($result) < 0) {
            $sql = "select `scene_id` from " . WEIXIN_TABLE . " order by id desc limit 1";
            $scene_id = $mysql->query($sql, '1');
            $scene_id = $scene_id % 5 + 1;
            $qrcode = $weObj->getQRCode($scene_id, $type=0, $expire=1800);
            $ticket = $qrcode['ticket'];
            $sql = "insert into " . WEIXIN_TABLE . " (`Mac_ID`, `ticket`, `scene_id`,  `site`)
                    values ('{$Mac_ID}', '{$ticket}', '{$scene_id}', '{$site}')";
        } else {
            $qrcode = $weObj->getQRCode($result[0]['scene_id'], $type=0, $expire=1800);
            $ticket = $qrcode['ticket'];
            $sql = "update " . WEIXIN_TABLE . " set `ticket` = '{$ticket}' where `Mac_ID` = '{$Mac_ID}'";
        }

        $img = $weObj->getQRUrl($ticket);
        $mysql->query($sql);
        echo "<img src='{$img}'>";
        echo "<br>";
        echo "ps: expired after 30 min";
        echo "<br>";
        echo "<br>";
        echo "cellphone just followed the Wechat.......";
        exit();
        
    }
    $sql = "insert into " . WEIXIN_TABLE . " (`Mac_ID`, `fromUserName`)
            values ('{$Mac_ID}', '{$fromUserName}')";
    $mysql->query($sql);
}
UniFi::set_site($site);
UniFi::sendAuthorization($Mac_ID, WIFI_EXPIRED_TIME);
$sql = "update " . WEIXIN_TABLE . " set `ticket` = 'authorized' where `fromUserName` = '{$fromUserName}'";
$mysql->query($sql);

sleep(5);
header('Location: ' . DEFAULT_URL);
exit();
