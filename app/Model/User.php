<?php
/**
 * 用户
 * 
 * @author ShuangYa
 * @package Example
 * @category Model
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2019 ShuangYa
 */
namespace App\Model;

use Sy\ModelAbstract;

class User extends ModelAbstract {
	protected $_table_name = 'user';
	protected $_primary_key = 'id';
}