<?php
/**
 * 首页
 * 
 * @author ShuangYa
 * @package Example
 * @category Public
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
error_reporting(E_ALL &~ E_NOTICE);
define('PROJECT_PATH', realpath(__DIR__ . '/..') . '/');
define('APP_PATH', PROJECT_PATH . 'app/');
define('APP_ENV', 'develop');
define('PUBLIC_PATH', __DIR__ . '/');
require(PROJECT_PATH . '/vendor/autoload.php');

Sy\App::create(require(APP_PATH . 'Config.php'));
