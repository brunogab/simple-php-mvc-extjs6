<?php
namespace GApp\Models;

/**
 * class UserProfileModel
 */
final class UserProfileModel
{
	//use FunctionsTrait;

	private $db;
	
	public function __construct($mypdo)
	{
		$this->db = $mypdo; 
	}

	public function getMDB()
	{
		return $this->db;
	}

	/**
	 * def data type
	 */
	public function getColumnNameDataType(){
		return [
			'id' => 'id',
			'ucode' => 'string',
			'uname' => 'string',
			'email' => 'email',
			'telefon' => 'string',
			'superu' => 'bool',
			'locked' => 'bool',
			'wronglogin' => 'integer',
			'pwd' => 'string',
			'updatepwd' => 'string',
			'isdefpw' => 'bool',
			'fn' => 'string',
			'createdate' => 'string',
			'createucode' => 'string',
			'updatedate' => 'string',
			'updateucode' => 'string',

			'dtime' => 'string',
			'host' => 'string',
			'hostip' => 'string',
			'typ' => 'string',

			'pname' => 'string',
			'pvalue' => 'string'
		];
	}

	/**
	 * data type
	 * get associative array from database
	 */
	/*
	public function getColumnNameDataType($table){
		$config = \GApp\Lib\Config::get('database');
		$db = $config['DB_NAME'];
		//$query_result = $this->db->sql_query("SHOW COLUMNS FROM $table FROM $db");
		$query_result = $this->db->sql_query("SELECT LOWER(COLUMN_NAME) AS 'field', LOWER(DATA_TYPE) AS 'fieldtype' FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table'");
		if (!$query_result) {
			return false;
		}

		return $query_result->fetchAll(\PDO::FETCH_KEY_PAIR);
	}
	*/

	/**
	 * return user data
	 */
	public function getListing()
	{
		$query_result = $this->db->sql_query("SELECT 
			T0.id, T0.Ucode AS 'ucode', T0.Uname AS 'uname', T0.Email AS 'email', '' AS 'pwd',
			T0.CreateDate AS 'createdate', T0.CreateUcode AS 'createucode', 
			T0.UpdateDate AS 'updatedate', T0.UpdateUcode AS 'updateucode'
			FROM users T0 WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin",array(':ucode' => $_SESSION['user_code']));
		if (!$query_result) {
			return false;
		}

		return $query_result->fetchAll();
	}

	/**
	 * helper for check consistent data @postData @table @column
	 */
	public function checkExistingData($input_data, $table, $column)
	{
		$query_result = $this->db->sql_query("SELECT T0.id FROM $table T0 WHERE T0.id <> :id AND LOWER(T0.$column) collate utf8mb4_bin = LOWER(:$column) collate utf8mb4_bin ", 
												array(':id' => $input_data['id'], ":".$column => $input_data[$column]));
		if (!$query_result) {
			return false;
		}

		return ['count' => $query_result->rowCount()];
	}

	/**
	 * update form - data
	 */
	public function setUserData($input_data)  
	{
		$query_result = $this->db->sql_query("UPDATE users T0 SET
																T0.UName		= :uname,
																T0.Email		= :email,
																T0.UpdateDate	= :updatedate,
																T0.UpdateUcode	= :updateucode
														WHERE T0.id = :id",
														array(  ':id'			=> $input_data['id'],
																':uname'		=> $input_data['uname'],
																':email'		=> $input_data['email'],
																':updatedate'	=> $input_data['updatedate'],
																':updateucode'	=> $input_data['updateucode']
															)
											);
		if (!$query_result) {
			return false;
		}
	 
		return $query_result;
	}

	/**
	 * update form - pwd
	 */
	public function setUserPassword($input_data)
	{
		$query_result = $this->db->sql_query("UPDATE users T0 SET
					T0.Pwd			= :pwd,
					T0.UpdatePwd	= :updatepwd,
					T0.IsDefPW		= :isdefpw
			WHERE T0.id = :id", 
			array(  ':id'			=> $input_data['id'],
					':pwd'			=> $input_data['pwd'],
					':updatepwd'	=> $input_data['updatepwd'],
					':isdefpw'		=> $input_data['isdefpw']
				)
		);
		if (!$query_result) {
			return false;
		}

		return $query_result;
	}

	/**
	 * select tab 1 table
	 */
	public function getListingLogAll($ret)
	{
		//var_dump($ret);
		extract($ret);
		$query_s = '';
		if (!empty($searchtext)) {
			$query_s = " AND    (
									LOWER(T0.Ucode)		collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin OR
									LOWER(T0.Host)		collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin OR
									LOWER(T0.HostIp)	collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin
								)";
		}

		/**select all affected rows for extjs total property */
		$query_nbrows = $this->db->sql_query("SELECT * FROM log T0 WHERE '0'='0' $query_s");
		if (!$query_nbrows) {
			return false;
		}

		$query_result = $this->db->sql_query("SELECT T0.id AS 'id', T0.Dtime AS 'dtime', T0.Ucode AS 'ucode', T0.Host AS 'host', T0.HostIp AS 'hostip', T0.Typ AS 'typ'
												FROM log T0
												WHERE '0'='0' $query_s
												ORDER BY $sort $dir LIMIT :start , :end", array(':start' => $start, ':end' => $end));
		if (!$query_result) {
			return false;
		}

		return ['nbrows' => $query_nbrows->rowCount(), 'query_result' => $query_result->fetchAll()];
	}

	/**
	 * select tab 1 table by user
	 */
	public function getListingLogUser($ret, $usercode) 
	{
		extract($ret); 
		$query_s = '';
		if (!empty($searchtext)) {
			$query_s = " AND    (
									LOWER(T0.Ucode)		collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin OR
									LOWER(T0.Host)		collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin OR
									LOWER(T0.HostIp)	collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin
								)";
		}

		$query_nbrows = $this->db->sql_query("SELECT * FROM log T0 WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin $query_s", array(':ucode' => $usercode));
		if (!$query_nbrows) {
			return false;
		}

$query_result = $this->db->sql_query("SELECT T0.id AS 'id', T0.Dtime AS 'dtime', T0.Ucode AS 'ucode', T0.Host AS 'host', T0.HostIp AS 'hostip', 
													case when T0.Typ = 'Y' then 'ok' else 'bad' end as 'typ'
												FROM log T0
												WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin $query_s
												ORDER BY $sort $dir LIMIT :start , :end", array(':ucode' => $usercode,':start' => $start, ':end' => $end));
		if (!$query_result) {
			return false;
		}

		return ['nbrows' => $query_nbrows->rowCount(), 'query_result' => $query_result->fetchAll()];
	}

	/**
	 * select tab 2 table
	 */
	public function getListingUserPrivilages($ret, $usercode)
	{
		extract($ret);
		$query_s = '';
		if (!empty($searchtext)) {
			$query_s = " AND    (
									LOWER(T0.Pname)		collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin OR
									LOWER(T0.Pvalue)	collate utf8mb4_bin LIKE LOWER('%$searchtext%') collate utf8mb4_bin
								)";
		}

		$query_nbrows = $this->db->sql_query("SELECT * FROM uprv T0 WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin $query_s", array(':ucode' => $usercode));
		if (!$query_nbrows) {
			return false;
		}

		$query_result = $this->db->sql_query("SELECT T0.id AS 'id', T0.Pname AS 'pname', T0.Pvalue AS 'pvalue'
												FROM uprv T0
												WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin $query_s
												ORDER BY $sort $dir LIMIT :start , :end", array(':ucode' => $usercode,':start' => $start, ':end' => $end));
		if (!$query_result) {
			return false;
		}

		return ['nbrows' => $query_nbrows->rowCount(), 'query_result' => $query_result->fetchAll()];
	}

}
