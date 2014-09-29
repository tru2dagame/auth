<?php

session_start();

include_once('../../config.php');
include_once(WEIBO_PATH . '/saetv2.ex.class.php');

$array_tmp = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
$site = end($array_tmp);
$site = is_string($site) ? $site : 'default';

$Mac_ID = isset($_GET['id']) ? addslashes($_GET['id']) : '';
if (!$Mac_ID) {
    header('Location: ' . DEFAULT_URL);
    exit();
}

$o = new SaeTOAuthV2( WEIBO_AKEY , WEIBO_SKEY );

if (isset($_REQUEST['code'])) {
    $keys = array();
    $keys['code'] = $_REQUEST['code'];
    $keys['redirect_uri'] = WEIBO_CALLBACK_URL;
    try {
        $token = $o->getAccessToken( 'code', $keys ) ;
    } catch (OAuthException $e) {

    }
}

if ($token) {
    $from_user_name = $c->get_uid();
    if (!$fromUserName) {
        header('Location: ' . DEFAULT_URL);
        exit();
    }
    $sql = "select * from " . WEIBO_TABLE . " where `Mac_ID` = '{$Mac_ID}' and `fromUserName` = '{$from_user_name}";
    $res = $mysql->query($sql, 'all');

    if (!is_array($res) || count($res) <= 0) {
        $sql = "insert into " . WEIXIN_TABLE . " (`Mac_ID`, `fromUserName`)
                values ('{$Mac_ID}', '{$fromUserName}')";
        $mysql->query($sql);
        setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
        $c = new SaeTClientV2( WEIBO_AKEY , WEIBO_SKEY , $_SESSION['token']['access_token'] );
        $follow = $c->follow_by_name(WEIBO_NAME);//关注用户
        $send = $c->update(WEIBO_MESSAGE);//发送微博
        if(isset($follow['error_code'])
            && $follow['error_code'] > 0) {
            echo WEIBO_FOLLOW_ERROR_MESSAGE;exit;
        } else if(isset($send['error_code'])
            && $send['error_code'] > 0 ) {
            echo WEIBO_SEND_ERROR_MESSAGE;exit;
        }
    }

    UniFi::set_site($site);
    UniFi::sendAuthorization($Mac_ID, WIFI_EXPIRED_TIME);
    sleep(5);
    header('Location: ' . DEFAULT_URL);
} else {
    echo '授权失败。';
}