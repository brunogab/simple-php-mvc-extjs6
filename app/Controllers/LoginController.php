<?php
namespace GApp\Controllers;

use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class LoginController
 */
final class LoginController
{
	use FnTrait, ResponseTrait;
	
	private $config;
	private $template;
	private $model;

	public function __construct($config, $template, $model)
	{
		$this->config   = $config;
		$this->template = $template;
		$this->model    = $model;
	}

	public function getView()
	{
		$this->config['viewpage'] = 'login';
		$this->template->template_render($this->config['path.views'] . "/main.php", $this->config);
	}


	/**
	 * Loginform post function
	 */
	public function postLoginForm()
	{
		/**
		 * get Column Info 
		 */
		$column_info = $this->model->getColumnNameDataType();

		/**
		 * POST data, sanitize-validate automatically by column_info
		 */
		$input_data = $this->validate($_POST)->with($column_info)->get();

		/**
		 * add some another data or format 
		 */
		$input_data['ucode'] = $this->fn('strtoupper', $input_data['ucode']);

		/** set cookies */
		$this->fn('setCookie', ['usercode', $input_data['ucode']]);

		/**
		 * check for null data, following colummns in array will be checked
		 */
		$notnull_col = ['ucode','pwd'];
		foreach ($notnull_col as $key) {
			if (empty($input_data[$key])) {
				return $this->response('error', 'Could not get or not valid: ' . $key);
			}
		}


		/** 
		 * valid pw data
		 */
		$valid_password = $this->fn('validPassword', $input_data['pwd']);
		if(!$valid_password) {
			return $this->response('error', $this->fn('getMsg')); 
		}
				
		/**
		 * get User Data
		 */
		$query_result = $this->model->getUserData($input_data['ucode']);
		if ($query_result===false) {
			return $this->response('error', $this->model->getMDB()->getMsg()); 
		}

		/**
		 * check User Data, //https://www.php.net/empty
		 */
		if (empty($query_result)) {
			return $this->response('error', 'Not found: ' . $input_data['ucode']); 
		}

		/** push result into array */
		$user_row = $query_result[0];
		
		/**
		* check user for locked
		*/
		if ((bool)$user_row['locked']) {
			return $this->response('error', 'User is locked: ' . $input_data['ucode']); 
		}

		/**
		* check user for wronglogin
		*/
		if ((int)$this->config['user.login.max.try']>0) {
			if ((int)$user_row['wronglogin'] >= (int)$this->config['user.login.max.try']) {
				return $this->response('error', 'User is locked: ' . $input_data['ucode']); 
			}
		}

		/**
		 * verify password
		 */
		if(password_verify($input_data['pwd'],$user_row['pwd'])) {

			/**
			 * reset wrong login counter 
			 */
			$query_result = $this->model->setGoodLogin($input_data['ucode']);
			if (!$query_result) {
				return $this->response('error', $this->model->getMDB()->getMsg()); 
			}


			/**
			 * pass variable into session
			 */
			$_SESSION['user_code'] 		= $user_row['ucode'];
			$_SESSION['user_name'] 		= $user_row['uname'];
			$_SESSION['user_updatepwd'] = $user_row['updatepwd'];
			$_SESSION['user_isdefpw']	= $user_row['isdefpw'];
			$_SESSION['user_is_superu']	= $user_row['superu'];
			$_SESSION['user_logged_in'] = true;


			/**
			 * regenerate session 
			 */
			session_regenerate_id(true);

			/**
			 * finally response a success with reload
			 */
			return $this->response('success', 'reload');

		}else {

			/**
			 * increase wrong login counter 
			 */
			$query_result = $this->model->setWrongLogin($input_data['ucode']);
			if (!$query_result) {
				return $this->response('error', $this->model->getMDB()->getMsg()); 
			}
	

			/**
			 * finally response a Fail
			 */
			$user_row['wronglogin'] = $user_row['wronglogin'] + 1;
			return $this->response('error', 'Invalid login. Attempt: ' . $user_row['wronglogin']." / ".$this->config['user.login.max.try']); 
			
		}	

		return; //(does never reach this point)
	}

}
