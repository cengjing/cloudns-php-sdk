# Cloudns-php-sdk
YY ClouDNS是YY游戏的DNSaaS服务，是专注于游戏运营、以及私有云服务的DNS解析系统。
使用高可用的服务器集群和云同步技术，保证业务访问更加稳定无忧。
项目主页: https://dnscp.duowan.com/

本项目为YY ClouDNS的PHP版本的SD

## 安装

### Via Composer
可以通过composer进行安装,Cloudns-php-sdk已经在[Packagist](https://packagist.org/packages/duowan/cloudns-php-sdk)上
通过composer命令进行安装:
```shell
composer require  duowan/cloudns-php-sdk  dev-master
```
安装完成之后,可以在 vendor/duowan/cloudns-php-sdk 中找到项目文件.


## 使用说明
### 获得账号和token
参见  : [API文档](https://dnscp.duowan.com/download/cloudns-api-2013.11v1.2.pdf) 中的 `1.3 使用方法` 一节

### 通过composer安装的程序	
```php
// 引入composer提供的自动加载器
require 'vendor/autoload.php';
use ClouDNS\CloudnsSDK;
/**
 * 配置信息
 * 配置信息主要写在 : 项目路径 .
 * src/config.php 中
 * 可以通过项目运行时,动态传入配置.传入的配置,将会覆盖config.php中的同名的配置
 * 如果所有配置信息都在config.php文件中有效指明,则运行CloudnsSDK::init();时可不传入$config数组
 */
$config = array(
    'auth' => array(
        'psp' => 'your passport',
        'tkn' => 'your token'
    )
);
CloudnsSDK::init($config);
// 获得user对象
$user = CloudnsSDK::user();
// 调用user对象中的rec_load_all方法
$ret = $user->userlog_load_all();
// 获得zone对象
$zone = CloudnsSDK::zone();
// 调用record对象中的rec_load_all方法
$ret = $zone->zone_load_multi();
// 获得record对象
$record = CloudnsSDK::record();
// 调用record对象中的rec_load_all方法
$ret = $record->rec_load_all('zone.com');
```

### 接口使用
具体接口使用说明请参考 [API文档](https://dnscp.duowan.com/download/cloudns-api-2013.11v1.2.pdf) , 所有SDK中的方法名称和API文档中的方法名称一致.
