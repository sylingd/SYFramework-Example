<?php
/**
 * Token
 * 
 * @author ShuangYa
 * @package Example
 * @category Service
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Service;

use App\Model\User;
use App\Model\Token as TokenModel;

class Token {
	private $user;
	private $token;
	public function __construct(User $user, TokenModel $token) {
		$this->user = $user;
		$this->token = $token;
	}

	public function create($id) {
		$token = bin2hex(random_bytes(10));
		$this->token->add([
			'user' => $id,
			'token' => $token,
			'create_time' => date('Y-m-d H:i:s')
		]);
		return $token;
	}

	public function validate($token) {
		$res = $this->get(['token' => $token], ['user']);
		return $res === null ? null : $this->user->get($res['user']);
	}

	public function get($filter, $field = null) {
		return $this->token->get($filter, $field);
	}
}