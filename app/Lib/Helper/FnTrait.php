<?php

namespace GApp\Lib\Helper;

/**
 * class Functions for all class
 */
trait FnTrait
{
	private $msg;
	private $finaldata = [];
	private $cookiedata = [];

	private function fn($fn, $param = '')
	{
		return $this->$fn($param);
	}

	private function session_destroy()
	{
		$_SESSION = [];
		session_regenerate_id(true);
		session_destroy();
		return;
	}

	private function cookie()
	{
		$this->cookiedata = [];
		if (!empty($_COOKIE[COOKIENAME])) {
			$this->cookiedata = json_decode($_COOKIE[COOKIENAME], true);
		}
		return;
	}

	private function setCookie($param)
	{
		$this->cookie();
		if (empty($param)) {
			return false;
		}
		$this->cookiedata[$param[0]] = $param[1];
		setcookie(COOKIENAME, json_encode($this->cookiedata), strtotime('+90 days'), null, null, null, true);
		return true;
	}

	private function getCookie($return_key = '')
	{
		$this->cookie();
		if (empty($this->cookiedata)) {
			return null;
		}

		if (empty($return_key)) {
			return $this->cookiedata;
		}

		if (array_key_exists($return_key, $this->cookiedata)) {
			return  $this->cookiedata[$return_key];
		}
		//return $this->cookiedata; //array key was not found return with array
		return null; //array key was not found return with null
	}

	private function setMsg($msg)
	{
		$this->msg = $msg;
		return true;
	}

	private function getMsg()
	{
		return $this->msg;
	}

	/**
	 * @input = array,
	 * with() = array or string, (for ex int, float, id, bool..) -> custom filter
	 * get() = string, requested key from @input array.  if empty then return @input_array
	 */
	//use..
	//$val = ['a'=>'test', 'b'=>'', 'c'=>'16.235', 'd'=>'true', 'e'=>'1616'];
	//$arr = ['a'=>'string', 'b'=>'int', 'c'=>'float', 'd'=>'bool'];
	//$get = $this->validate($val)->with($arr)->get();
	//$get = $this->validate($val)->with()->get('c');
	//$get = $this->validate($val)->get('c');
	//$get = $this->validate($val)->get();

	/**
	 * 1. calculate type of value
	 */
	private function validate($input_data)
	{
		if (!empty($input_data)) {
			foreach ($input_data as $key => $value) {
				//print "Validate Key: $key | Value: $value <br>";
				$this->finaldata[$key] = $this->validate_gettype($value);
			}
		}
		return $this;
	}

	/**
	 * 1. type is given by array or string
	 * 2. filter
	 * 3. custom filter
	 */
	private function with($data_type = '')
	{
		$get_type = '';

		/** data_type empty return */
		if (empty($data_type)) {
			return $this;
		}

		/** data_type in given string */
		if (is_string($data_type)) {
			$get_type = $data_type;

			foreach ($this->finaldata as $key => $value) {
				//print "Width Key: $key | Value: $get_type <br/>";
				$this->finaldata[$key] = $this->validate_filter($value, $get_type);
			}
		}

		if (is_array($data_type)) {
			foreach ($this->finaldata as $key => $value) {
				if (array_key_exists($key, $data_type)) {
					$get_type = $data_type[$key];
					//print "Width Key: $key | Value: $get_type <br/>";
					$this->finaldata[$key] = $this->validate_filter($value, $get_type);
				}
			}
		}

		return $this;
	}

	/**
	 * return array or array[$key]
	 */
	private function get($return_key = '')
	{
		if (empty($this->finaldata)) {
			return null;
		}

		if (empty($return_key)) {
			return $this->finaldata;
		}

		if (array_key_exists($return_key, $this->finaldata)) {
			return  $this->finaldata[$return_key];
		}
		//return $this->finaldata; //array key was not found return with array
		return null; //array key was not found return with null
	}

	/**
	 * calculate type -> filter
	 */
	private function validate_gettype($value)
	{
		/*
			https://www.php.net/manual/en/function.gettype.php

			"boolean"
			"integer"
			"double" (for historical reasons "double" is returned in case of a float, and not simply "float")
			"string"
			"array"
			"object"
			"resource"
			"resource (closed)" as of PHP 7.2.0
			"NULL"
			"unknown type"
		*/

		if (filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false) {
			$value = (bool) $value;
		} elseif (filter_var($value, FILTER_VALIDATE_INT) !== false) {
			//$value = (int) $value;
			$value = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
		} elseif (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
			//$value = (float) $value;
			$value = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		} elseif (is_string($value)) {
			//$value = (string) $value;
			$value = (string) filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		} else {
			$value = (string) filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		}

		return $value;
	}

	/**
	 * custom filter to given gettype
	 */
	private function validate_filter($value, $get_type)
	{
		/**
		 * valid int or 0
		 */
		if (in_array($get_type, ['int', 'integer'])) {
			$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
			return filter_var($value, FILTER_VALIDATE_INT) ? (int) $value : (int) 0;
		}

		/**
		 * valid float or 0
		 */
		if (in_array($get_type, ['float', 'double'])) {
			$value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			return filter_var($value, FILTER_VALIDATE_FLOAT) ? (float) $value : (float) 0;
		}

		/**
		 * valid to string or null
		 */
		if (in_array($get_type, ['str', 'string'])) {
			return !empty($value) ? (string) filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : null;
		}

		/**
		 * valid to boolean using 'N'/'Y'
		 */
		if (in_array($get_type, ['bool', 'boolean'])) {
			return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? (string) 'Y' : (string) 'N';
		}

		/** CUSTOM sanitize and validate */

		/**
		 * valid table id int or -1
		 */
		if ($get_type == 'id') {
			return filter_var($value, FILTER_VALIDATE_INT) ? (int) $value : (int) -1;
		}

		/**
		 * valid to email or null
		 */
		if ($get_type == 'email') {
			$value = filter_var($value, FILTER_SANITIZE_EMAIL);
			return filter_var($value, FILTER_VALIDATE_EMAIL) ? (string) $value : null;
		}

		/**
		 * valid to default or null
		 */
		return !empty($value) ? (string) filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : null;
	}

	/**
	 * helper for Grid-Query get input-post or input-get @data_type = ''
	 * return: @start, @end, @sort, @dir, @searchtext
	 */
	private function getParamsforGridQuery($param)
	{
		$input_data = $param[0];
		$data_type = $param[1];
		$input_data = $this->validate($input_data)->with($data_type)->get();

		$sort = $input_data['sort'];
		if (!empty($sort)) {
			$sort_post = json_decode($sort, true);
			$sort = $sort_post[0]['property'];
			$dir = $sort_post[0]['direction'];
		} else {
			$sort = $dir = '';
		}

		$searchtext = isset($input_data['searchtext']) ? $input_data['searchtext'] : '';

		return ['start' => $input_data['start'], 'end' => $input_data['limit'], 'sort' => $sort, 'dir' => $dir, 'searchtext' => $searchtext];
	}

	/**
	 * data into array
	 */
	private function fileGetContents()
	{
		return json_decode($this->trim(file_get_contents('php://input')), true);
	}

	/**
	 * valid password
	 */
	private function validPassword($pw = '')
	{
		$valid_ = true;

		$r1 = '/[A-Z]/';			//uppercase
		$r2 = '/[a-z]/';			//lowercase
		$r3 = '/[0-9]/';			//numbers
		$r4 = '/[^\da-zA-Z]/';	//special char

		if (preg_match_all($r1, $pw, $o) < 1) {
			$valid_ = false;
		}
		if (preg_match_all($r2, $pw, $o) < 1) {
			$valid_ = false;
		}
		if (preg_match_all($r3, $pw, $o) < 1) {
			$valid_ = false;
		}
		if (preg_match_all($r4, $pw, $o) < 1) {
			$valid_ = false;
		}
		if (strlen($pw) < 8) {
			$valid_ = false;
		}

		if (!$valid_) {
			$this->setMsg('The password does not meet the requirements of the password policy!<br />1. Minimum length: 8 characters<br />2. At least: a number, a uppercase letter and a lowercase letter<br />3. At least: a special character');
		}

		return $valid_;
	}

	/**
	 * hash password
	 */
	private function hashPassword($pw = '')
	{
		$hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]);
		if (!$hash) {
			$this->setMsg('password hash fail');
			return false;
		}
		return $hash;
	}

	/**-------------------------------------------------- */

	/**
	 * return trim
	 */
	private function trim($str)
	{
		return trim($str);
	}

	/**
	 * string to lower with mb
	 */
	private function strtolower($str)
	{
		return mb_strtolower($str);
	}

	/**
	 * string to upper with mb
	 */
	private function strtoupper($str)
	{
		return mb_strtoupper($str);
	}

	/**
	 * first letter to upper with mb
	 */
	private function ucfirst($str)
	{
		$str[0] = $this->strtoupper($str[0]);
		return $str;
	}

	/**
	 * return now datetime
	 */
	private function now()
	{
		return (new \DateTime())->format('Y-m-d H:i:s'); //date("Y-m-d H:i:s");
	}
}
