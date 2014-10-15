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
                         'token'=>WECHAT_TOKEN, //填写你设定的key
                         'appid'=>WECHAT_APPID, //填写高级调用功能的app id
                         'appsecret'=>WECHAT_APPSECRET, //填写高级调用功能的密钥
                         );

        $weObj = new Wechat($options);

        $sql = "select `Mac_ID`, `scene_id`, `created_at`, `updated_at`, `ticket` from " . WEIXIN_TABLE . " where `Mac_ID` = '{$Mac_ID}' and `ticket` != 'authorized' limit 1";
        $result = $mysql->query($sql, 'all');
        $expire = 500;
        if (!is_array($result) || count($result) < 0) {
            $sql = "select `scene_id` from " . WEIXIN_TABLE . " order by id desc limit 1";
            $scene_id = $mysql->query($sql, '1');
            $scene_id = $scene_id % 9999 + 1;
            $qrcode = $weObj->getQRCode($scene_id, $type = 0, $expire);
            $ticket = $qrcode['ticket'];
            $sql = "insert into " . WEIXIN_TABLE . " (`Mac_ID`, `ticket`, `scene_id`,  `site`)
                    values ('{$Mac_ID}', '{$ticket}', '{$scene_id}', '{$site}')";
        } else {
            $created_at = $result[0]['created_at'];
            $updated_at = $result[0]['updated_at'];
            $now = time();
            if (($now - strtotime($created_at) > $expire) && ($now - strtotime($updated_at) > $expire)) {
                $qrcode = $weObj->getQRCode($result[0]['scene_id'], $type=0, $expire);
                $ticket = $qrcode['ticket'];
                $updated_at = date("Y-m-d H:i:s");
                $sql = "update " . WEIXIN_TABLE . " set `ticket` = '{$ticket}', `updated_at` = '{$updated_at}' where `Mac_ID` = '{$Mac_ID}'";

            } else {
                $ticket = $result[0]['ticket'];
            }
        }

        $img = $weObj->getQRUrl($ticket);
        $mysql->query($sql);
        echo "<img src='{$img}'>";
        echo "<br>";
        echo "ps: expired after 30 min";
        echo "<br>";
        echo "<br>";
        echo "cellphone just followed the Wechat.......";
?>
<img id="testImg" style="display:none">
<script>
var testImg = document.getElementById('testImg');
testImg.src = 'http://www.ubnt.com.cn/static/images/promos-content-left.png?v=' + (+ new Date());
var timer = setInterval(function(){
    testImg.src = 'http://www.ubnt.com.cn/static/images/promos-content-left.png?v=' + (+ new Date());
}, 2000);
testImg.onload = function(){
    clearInterval(timer);
    window.location.href = 'http://www.baidu.com';
};
</script>
<?php
        exit();

    }
    $sql = "update " . WEIXIN_TABLE . " set `fromUserName` = '{$fromUserName}' where `Mac_ID` = '{$Mac_ID}'";
    echo $sql;
    $mysql->query($sql);
}
UniFi::set_site($site);
UniFi::sendAuthorization($Mac_ID, WIFI_EXPIRED_TIME);
$sql = "update " . WEIXIN_TABLE . " set `ticket` = 'authorized' where `fromUserName` = '{$fromUserName}'";
$mysql->query($sql);

sleep(5);
header('Location: ' . DEFAULT_URL);
exit();
