<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
	$follow = $c->follow_by_id(3895345151);	//关注用户
	// $send = $c->update('test');	//发送微博
	if ( isset($follow['error_code']) && $follow['error_code'] > 0 && isset($send['error_code']) && $send['error_code'] > 0 ) {
		// echo "<p>关注失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		require_once ('../../../unifi/authorized.php');
		sendAuthorization($_SESSION['id'], '2');
    	sleep(5);
    	header('Location: http://115.236.16.74/guest/s/default/success.php');
	}
} else {
?>
授权失败。
<?php
}
?>
