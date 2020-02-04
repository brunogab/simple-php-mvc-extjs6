<?php
namespace GApp\Controllers;

use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class UserProfileController
 */
final class UserProfileController
{
	use FnTrait, ResponseTrait;

	private $config;
	private $template;
	private $model;

	public function __construct($config, $template, $model)
	{
		$this->config	= $config;
		$this->template	= $template;
		$this->model	= $model;
	}

	public function getView()
	{
		$this->config['viewpage'] = 'userprofile';
		$this->template->template_render($this->config['path.views'] . "/main.php", $this->config);
	}

	/**
	 * lock app response
	 */
	public function Lock()
	{
		$_SESSION['user_logged_in'] = false;
		$_SESSION['app_session_destroy'] = true;
		return $this->response('success', '');
	}

	/**
	 * read form
	 */
	public function Listing()
	{
		$query_result = $this->model->getListing();
		if (!$query_result) {
			return $this->response('error', $this->model->getMDB()->getMsg()); 
		}
		
		$jsonresult = json_encode($query_result); 
		$jsonresult = str_replace(array('[',']'),'',$jsonresult); 

		if (empty($jsonresult)) {
			return $this->response('error', 'result is empty!'); 
		}

		return $this->response('successData', $jsonresult);
	}

	/**
	 * save form
	 */
	public function Save()
	{
		/** alternative use https://packagist.org/packages/respect/validation */

		/**
		 * sanitize manual
		 */
		/*$input_data = filter_input_array(INPUT_POST, [
			"id"    => FILTER_SANITIZE_NUMBER_INT,
			"ucode" => FILTER_SANITIZE_STRING,
			"uname" => FILTER_SANITIZE_STRING,
			"email" => FILTER_SANITIZE_EMAIL,
			"pwd"   => FILTER_SANITIZE_STRING
		]);*/

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
		$input_data['updatedate']   = $this->fn('now');
		$input_data['updateucode']  = $_SESSION['user_code'];
		$input_data['updatepwd']    = $this->fn('now');

		/**
		 * check for null data, following colummns in array will be checked
		 */
		$notnull_col = ['id','uname','email'];
		foreach ($notnull_col as $key) {
			if (empty($input_data[$key])) {
				return $this->response('error', 'Could not get or not valid: ('.$key.')');
			}
		}

		/**
		 * check for existing data, following colummns in array will by checked
		 */
		$check_col= ['uname','email'];
		foreach ($check_col as $key) {
			$query_result = $this->model->checkExistingData($input_data, 'users', $key);
			if (!$query_result) {
				return $this->response('error', $this->model->getMDB()->getMsg());
			}
			if ($query_result['count'] > 0){
				return $this->response('error', 'It already exists: <b>'.$input_data[$key].'</b>');
			}
		}

		/**
		 * create Password
		 */
		$info = '';
		if (!empty($input_data['pwd'])) {
			
			$valid_password = $this->fn('validPassword', $input_data['pwd']);
			if (!$valid_password) {
				return $this->response('error', $this->fn('getMsg')); 
			}
			$input_data['pwd'] = $this->fn('hashPassword', $input_data['pwd']);
			if (!$input_data['pwd']) {
				return $this->response('error', $this->fn('getMsg')); 
			}

			$input_data['isdefpw'] = 'N';
			$query_result = $this->model->setUserPassword($input_data);
			if (!$query_result) {
				return $this->response('error', $this->model->getMDB()->getMsg());
			}
			$info = 'Password has been saved!';
			/** refresh session data */
			$_SESSION['user_updatepwd'] = $this->fn('now');
			$_SESSION['user_isdefpw']   = false;
	
		}

		$query_result = $this->model->setUserData($input_data);
		if (!$query_result) {
			return $this->response('error', $this->model->getMDB()->getMsg());
		}
		/** refresh session data */
		$_SESSION['user_name'] = $input_data['uname'];

		session_regenerate_id(true);

		return $this->response('success', $info);
	}


	/**
	 * Tab 1 table read
	 */
	public function ListingLog()
	{
		/**
		 * get Column Info
		 */
		$column_info = $this->model->getColumnNameDataType();
		$column_info = array_merge($column_info, $this->config['datatype']);

		/**
		 * get POST or GET data for Query @start, @end, @sort, @dir, @searchtext
		 */
		$input_data = $this->fn('getParamsforGridQuery', [$_POST, $column_info]); 


		if ($_SESSION['user_is_superu']) {
			$query_result = $this->model->getListingLogAll($input_data);
		}else {
			$query_result = $this->model->getListingLogUser($input_data, $_SESSION['user_code']);
		}
		if (!$query_result) {
			return $this->response('error', $this->model->getMDB()->getMsg());
		}
		
		$nbrows = $query_result['nbrows'];

		if ($nbrows>0) {
			$jsonresult = json_encode($query_result['query_result']);
			return $this->response('successTotal', [$nbrows, $jsonresult]);
		}else {
			return $this->response('successTotalEmpty');
		}

		return;
	}

	/**
	 * Tab 2 table read
	 */
	public function ListingUserPrivileges()
	{
		/**
		 * get Column Info
		 */
		$column_info = $this->model->getColumnNameDataType();
		$column_info = array_merge($column_info,$this->config['datatype']);

		/**
		 * get POST or GET data for Query @start, @end, @sort, @dir, @searchtext
		 */
		$input_data = $this->fn('getParamsforGridQuery', [$_POST, $column_info]);

		$query_result = $this->model->getListingUserPrivilages($input_data, $_SESSION['user_code']);
		if (!$query_result) {
			return $this->response('error', $this->model->getMDB()->getMsg());
		}
		
		$nbrows = $query_result['nbrows'];

		if ($nbrows>0) {
			$jsonresult = json_encode($query_result['query_result']);
			return $this->response('successTotal', [$nbrows, $jsonresult]);
		}else {
			return $this->response('successTotalEmpty');
		}

		return;
	}

}
