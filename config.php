<?php

//UniFi 微信授权上网配置信息

/* 服务器配置 */
defined('SERVER_HOST') or define('SERVER_HOST', '');

/* 微信 开发者中心->服务器配置 Token值 */
defined('WECHAT_TOKEN') or define('WECHAT_TOKEN', '');

/* UniFi 配置 */
defined('UNIFI_SERVER') or define('UNIFI_SERVER', '');
defined('UNIFI_USER') or define('UNIFI_USER', '');
defined('UNIFI_PASSWORD') or define('UNIFI_PASSWORD', '');

/* wifi有效时间 */
defined('WIFI_EXPIRED_TIME') or define('WIFI_EXPIRED_TIME', 60);//分钟

/* 默认跳转页面 */
defined('DEFAULT_URL') or define('DEFAULT_URL', 'http://www.ubnt.com.cn');

/* 配置mysql */
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');
defined('DB_DBNAME') or define('DB_DBNAME', '');
defined('DB_PORT') or define('DB_PORT', '3306');

defined('DB_TABLE') or define('DB_TABLE', '');//表名




//-----------------------------------系统配置（不要修改）-----------------------------------------
/* 路径和必要文件 */
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));
defined('DEPS_PATH') or define('DEPS_PATH', ROOT_PATH . '/deps');
defined('TEMPLATE_PATH') or define('TEMPLATE_PATH', ROOT_PATH . '/template');
defined('SITES_PATH') or define('SITES_PATH', ROOT_PATH . '/s');
defined('WEIXIN_PATH') or define('WEIXIN_PATH', ROOT_PATH . '/weixin-sdk');
/* cookie 存放路径 */
defined('COOKIE_FILE_PATH') or define('COOKIE_FILE_PATH', ROOT_PATH . '/tmp/unifi_cookie');

include_once (DEPS_PATH . '/unifi.php');

include_once (DEPS_PATH . '/mysql.php');
$config = array(
    'host' => DB_HOST,
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'name' => DB_DBNAME,
    'port' => DB_PORT,
);
$mysql = new UbntMysql($config);