<?php
/**
 * Utils
 * 
 * @author ShuangYa
 * @package Example
 * @category Library
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Library;

class Utils {
	public static function getResult($info = null) {
		$result = [];
		if (is_array($info) && isset($info['error'])) {
			$result = $info;
			$result['success'] = false;
		} else {
			$result['success'] = true;
			$result['data'] = $info;
		}
		return json_encode($result);
	}
}