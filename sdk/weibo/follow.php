<?php

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );

$ret = $c->follow_by_id(3895345151);	//关注用户
if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
	echo "<p>关注失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
} else {
	echo "<p>关注成功</p>";
}
?>
