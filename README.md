UniFi 微信授权上网代码
===========

####服务器配置（本教程针对Linux服务器）
1.需要一台有公网固定ip的服务器

2.搭建LNMP（Linux+Nginx+MySQL+PHP）环境，可参照[lnmp](http://lnmp.org/install.html)

3.下载本次案例代码，将weixin重命名为guest，放到/home/wwwroot/目录下

####代码配置
1.配置config文件

	/* 服务器配置 */
	defined('SERVER_HOST') or define('SERVER_HOST', 'http://1.1.1.1');

	/* 微信 开发者中心->服务器配置 Token值 */
	defined('WECHAT_TOKEN') or define('WECHAT_TOKEN', 'test');

	/* UniFi 配置 */
	defined('UNIFI_SERVER') or define('UNIFI_SERVER', 'https://1.1.1.1:8443');
	defined('UNIFI_USER') or define('UNIFI_USER', 'username');
	defined('UNIFI_PASSWORD') or define('UNIFI_PASSWORD', 'password');

	/* wifi有效时间 */
	defined('WIFI_EXPIRED_TIME') or define('WIFI_EXPIRED_TIME', 60);//分钟

	/* 默认跳转页面 */
	defined('DEFAULT_URL') or define('DEFAULT_URL', 'http://www.ubnt.com.cn');

	/* 配置mysql */
	defined('DB_HOST') or define('DB_HOST', 'localhost');
	defined('DB_USERNAME') or define('DB_USERNAME', 'root');
	defined('DB_PASSWORD') or define('DB_PASSWORD', 'password');
	defined('DB_DBNAME') or define('DB_DBNAME', 'unifi');
	defined('DB_PORT') or define('DB_PORT', '3306');

	defined('DB_TABLE') or define('DB_TABLE', 'weixinTest');//表名

	/* 当用户发送此内容才能获取返回 */
	defined('WEIXIN_AUTH_MESSAGE') or define('WEIXIN_AUTH_MESSAGE', '我要上网');

	/* 关注后的欢迎内容 */
	defined('WEIXIN_ADD_WELCOME_MESSAGE') or define('WEIXIN_ADD_WELCOME_MESSAGE', '您好');

2.数据库配置。新建名为unifi的数据库，执行一下sql语句建表

	CREATE TABLE IF NOT EXISTS `unifi`.`weixinTest` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `Mac_ID` varchar(20) NOT NULL,
	   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	   `fromUserName` varchar(255) NOT NULL,
	   PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;