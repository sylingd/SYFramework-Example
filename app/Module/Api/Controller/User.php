<?php
/**
 * 用户
 * 
 * @author ShuangYa
 * @package Example
 * @category Controller
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Module\Api\Controller;

use Sy\ControllerAbstract;
use Sy\Http\Cookie;
use Sy\Http\Request;
use App\Library\Utils;
use App\Model\User as UserModel;
use App\Service\Token;
use Respect\Validation\Validator;

class User extends ControllerAbstract {
	private $user;
	private $token;
	public function __construct(UserModel $user, Token $token) {
		$this->user = $user;
		$this->token = $token;
	}

	public function meAction(Request $request) {
		$token = Cookie::get('token');
		$user = $this->token->validate($token);
		if ($user === null) {
			return Utils::getResult([
				'errno' => '101',
				'error' => 'Not login'
			]);
		}
		unset($user['password']);
		return Utils::getResult([
			'user' => $user
		]);
	}

	public function loginAction(Request $request) {
		$user = $this->user->get(['name' => $request->post['name']]);
		if ($user === null) {
			return Utils::getResult([
				'errno' => '101',
				'error' => 'User not exists'
			]);
		}
		if (!password_verify($request->post['password'], $user['password'])) {
			return Utils::getResult([
				'errno' => '102',
				'error' => 'Wrong password'
			]);
		}
		$token = $this->token->create($user['id']);
		Cookie::set([
			'name' => 'token',
			'value' => $token,
			'path' => '/',
			'expire' => 7 * 24 * 3600
		]);
		unset($user['password']);
		return Utils::getResult([
			'token' => $token,
			'user' => $user
		]);
	}

	public function registerAction(Request $request) {
		if (!preg_match('/^(\w{1,20})$/', $request->post['name'])) {
			return Utils::getResult([
				'errno' => '101',
				'error' => 'Invalid username'
			]);
		}
		if (!Validator::email()->validate($request->post['email'])) {
			return Utils::getResult([
				'errno' => '102',
				'error' => 'Invalid email'
			]);
		}
		$request->post['password'] = password_hash($request->post['password'], PASSWORD_DEFAULT);
		// ok
		try {
			$result = $this->user->add($request->post, ['name', 'password', 'email']);
		} catch (\Throwable $e) {
			return Utils::getResult([
				'errno' => '103',
				'error' => 'Register failed'
			]);
		}
		return Utils::getResult([
			'id' => $result
		]);
	}
}