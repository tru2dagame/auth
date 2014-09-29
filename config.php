<?php

//UniFi 微信和验证码授权上网配置信息

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

/* 微信消息和欢迎内容 */
defined('WEIXIN_AUTH_MESSAGE') or define('WEIXIN_AUTH_MESSAGE', '我要上网');//当用户发送此内容才能获取返回
defined('WEIXIN_ADD_WELCOME_MESSAGE') or define('WEIXIN_ADD_WELCOME_MESSAGE', '您好，欢迎关注UBNT！Ubiquiti Networks公司在全球范围内设计，制造和销售创新性的宽带无线解决方案。UBNT的产品包括具有颠覆意义的无线产品，如Bullet（TM），NanoStation（TM），以及其他结合基于内部开发、业界领先的产品设计核心技术，包括Air OS操作系统和频率自由技术（100MHz至10GHz的RF设计）。访问UBNT官方主页www.ubnt.com.cn 官方论坛bbs.ubnt.com.cn 官方商城store.ubnt.com.cn了解更多。');//关注后的欢迎内容

/* 配置mysql */
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_USERNAME') or define('DB_USERNAME', 'root');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');//数据库密码
defined('DB_DBNAME') or define('DB_DBNAME', '');//数据库库名
defined('DB_PORT') or define('DB_PORT', '3306');

defined('WEIXIN_TABLE') or define('WEIXIN_TABLE', '');//微信表名
defined('VERIFY_CODE_TABLE') or define('VERIFY_CODE_TABLE', '');//验证码表名






//-----------------------------------系统配置（不要修改）-----------------------------------------
/* 路径和必要文件 */
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__));
defined('DEPS_PATH') or define('DEPS_PATH', ROOT_PATH . '/deps');
defined('SITES_PATH') or define('SITES_PATH', ROOT_PATH . '/s');
defined('SDK_PATH') or define('SDK_PATH', ROOT_PATH . '/sdk');
defined('WEIXIN_PATH') or define('WEIXIN_PATH', SDK_PATH . '/weixin');
/* cookie 存放路径 */
defined('COOKIE_FILE_PATH') or define('COOKIE_FILE_PATH', ROOT_PATH . '/tmp/unifi_cookie');

include_once (DEPS_PATH . '/VerifyCode.php');
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
