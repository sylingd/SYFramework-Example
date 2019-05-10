<?php
/**
 * 配置
 * 
 * @author ShuangYa
 * @package Example
 * @category Configutation
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App;

use Sy\Http\Router;
use Psr\SimpleCache\CacheInterface;

class Configuration {
	const ROUTE_VER = 1;
	public function setRouter(CacheInterface $cache) {
		// If you want to cache defined routes
		// if ($cache->has('routes') && $cache->get('routes_ver') == self::ROUTE_VER) {
		// 	Router::from($cache->get('routes'));
		// 	return;
		// }
		Router::get('/api/user/me', 'api.user.me');
		Router::post('/api/user/login', 'api.user.login');
		Router::post('/api/user/register', 'api.user.register');
		Router::get('/me', 'index.index.me');
		Router::get('/login', 'index.index.loginPage');
		Router::post('/login', 'index.index.login');
		Router::get('/register', 'index.index.registerPage');
		Router::post('/register', 'index.index.register');
		Router::get('/', 'index.index.index');
		// $cache->set('routes', Router::dump());
		// $cache->set('routes_ver', self::ROUTE_VER);
	}
}