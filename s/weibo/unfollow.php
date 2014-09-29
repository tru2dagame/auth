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
    $from_user_name = $c->get_uid();
    if (!$fromUserName) {
        header('Location: ' . DEFAULT_URL);
        exit();
    }

    $sql = "select * from " . WEIBO_TABLE . " where `fromUserName` = '{$from_user_name}'";
    $res = $mysql->query($sql, 'all');

    if (is_array($res) && count($res) > 0) {
        $sql = "delete from " . WEIBO_TABLE . " where `fromUserName` = '{$from_user_name}'";
        $mysql->query($sql);
        foreach ($res as $weibo) {
            $id = isset($weibo['id']) ? $weibo['id'] : '';
            $Mac_ID = isset($weibo['Mac_ID']) ? $weibo['Mac_ID'] : '';
            if (!$Mac_ID || !$id) {
                continue;
            }
            UniFi::sendUnauthorization($Mac_ID);
        }
    }

    sleep(5);
    header('Location: ' . DEFAULT_URL);
} else {
    echo '授权失败。';
}