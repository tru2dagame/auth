<?php

session_start();

include_once('../../config.php');
include_once(WEIBO_PATH . '/saetv2.ex.class.php');

$array_tmp = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
$site = end($array_tmp);
$site = is_string($site) ? $site : 'default';

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
    $_SESSION['token'] = $token;
    setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
    $c = new SaeTClientV2( WEIBO_AKEY , WEIBO_SKEY , $_SESSION['token']['access_token'] );
    $follow = $c->follow_by_name(WEIBO_NAME);//关注用户
    $send = $c->update(WEIBO_MESSAGE);//发送微博
    if(isset($follow['error_code'])
       && $follow['error_code'] > 0) {
        echo WEIBO_FOLLOW_ERROR_MESSAGE;
    } else if(isset($send['error_code'])
              && $send['error_code'] > 0 ) {
        echo WEIBO_SEND_ERROR_MESSAGE;
    } else {
        UniFi::set_site($site);
        UniFi::sendAuthorization($_SESSION['id'], WIFI_EXPIRED_TIME);
        sleep(5);
        header('Location: ' . DEFAULT_URL);
    }
} else {
    echo '授权失败。';
}