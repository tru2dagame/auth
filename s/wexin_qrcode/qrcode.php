<?php
error_reporting(E_ALL);
include_once ('../../config.php');
include_once (WEIXIN_PATH . '/class/wechat.class.php');
echo 444;
$options = array(
                 'token'=>'UBNTXjNqxjty', //填写你设定的key
                 'appid'=>'wx1192836da9513680', //填写高级调用功能的app id
                 'appsecret'=>'795fe5cd06e4b50e7c1b438df4eb0cd7', //填写高级调用功能的密钥
                 );
        
$weObj = new Wechat($options);
$qrcode = $weObj->getQRCode($scene_id='1234',$type=0,$expire=1800);
echo 555;
$ticket = $qrcode['ticket'];
$img = $weObj->getQRUrl($qrcode['ticket']);
$sql = "insert into " . WEIXIN_TABLE . " (`Mac_ID`, `ticket`, `site`)
                values ('{$Mac_ID}', '{$ticket}', '{$site}')";
$mysql->query($sql);
echo "<pre>";
var_dump($ticket);
echo 555;
echo "<img src='{$img}'>";
echo "<br>";
echo "ps: expired after 30 min";
