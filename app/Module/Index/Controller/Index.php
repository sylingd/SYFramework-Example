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
namespace App\Module\Index\Controller;

use Sy\ControllerAbstract;
use Sy\Http\Cookie;
use Sy\Http\Request;
use App\Library\Utils;
use App\Model\User as UserModel;
use App\Service\Token;
use Respect\Validation\Validator;

class Index extends ControllerAbstract {
	private $user;
	private $token;
	public function __construct(UserModel $user, Token $token) {
		$this->user = $user;
		$this->token = $token;
	}

	public function indexAction(Request $request) {
		$this->display('Index/Index');
	}

	public function meAction(Request $request) {
		$token = Cookie::get('token');
		$user = $this->token->validate($token);
		if ($user === null) {
			$this->assign('message', 'Not login');
			$this->display('Index/Notice');
			return;
		}
		$this->assign('user', $user);
		$this->display('Index/Me');
	}

	public function loginPageAction(Request $request) {
		$this->display('Index/Login');
	}

	public function loginAction(Request $request) {
		$user = $this->user->get(['name' => $request->post['name']]);
		if ($user === null) {
			$this->assign('message', 'User not exists');
			$this->display('Index/Notice');
			return;
		}
		if (!password_verify($request->post['password'], $user['password'])) {
			$this->assign('message', 'Wrong password');
			$this->display('Index/Notice');
			return;
		}
		$token = $this->token->create($user['id']);
		Cookie::set([
			'name' => 'token',
			'value' => $token,
			'path' => '/',
			'expire' => 7 * 24 * 3600
		]);
		unset($user['password']);
		$this->assign('message', 'Login success');
		$this->display('Index/Notice');
	}

	public function registerPageAction(Request $request) {
		$this->display('Index/Register');
	}

	public function registerAction(Request $request) {
		var_dump($request->post);
		if (!preg_match('/^(\w{1,20})$/', $request->post['name'])) {
			$this->assign('message', 'Invalid username');
			$this->display('Index/Notice');
			return;
		}
		if (!Validator::email()->validate($request->post['email'])) {
			$this->assign('message', 'Invalid email');
			$this->display('Index/Notice');
			return;
		}
		$request->post['password'] = password_hash($request->post['password'], PASSWORD_DEFAULT);
		// ok
		try {
			$result = $this->user->add($request->post, ['name', 'password', 'email']);
		} catch (\Throwable $e) {
			$this->assign('message', 'Register failed');
			$this->display('Index/Notice');
			return;
		}
		$this->assign('message', 'Register success');
		$this->display('Index/Notice');
	}
}