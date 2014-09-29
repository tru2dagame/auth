<?php
session_start();

include_once('../../config.php');
include_once(WEIBO_PATH . '/saetv2.ex.class.php');

$o = new SaeTOAuthV2( WEIBO_AKEY , WEIBO_SKEY );
$code_url = $o->getAuthorizeURL( WEIBO_CALLBACK_URL );

$Mac_ID = isset($_GET['id']) ? addslashes($_GET['id']) : '';
if (!$Mac_ID) {
    header('Location: ' . DEFAULT_URL);
    exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ucc</title>
</head>

<body>
<!-- 授权按钮 -->
<p><a href="<?=$code_url?>"><img src="weibo_login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></p>

</body>
</html>
