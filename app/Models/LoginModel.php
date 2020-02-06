<?php

namespace GApp\Models;

/**
 * class LoginModel
 */
final class LoginModel
{
	private $db;

	public function __construct($mypdo)
	{
		$this->db = $mypdo;
	}

	/** return model db */
	public function getMDB()
	{
		return $this->db;
	}

	/**
	 * filter type (FILTER_SANITIZE_*) will be determined based on this associative array values
	 * for ex. for key 'ucode' value 'string'
	 *		filter for ucode will be FILTER_SANITIZE_STRING
	 *		so ucode is set to string, then PDO param for ucode will be PDO::PARAM_STR \lib\mypdo.php -> bind_arrayValues()
	 */
	public function getColumnNameDataType()
	{
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

			'langcode' => 'string'
		];
	}

	/** return user-data */
	public function getUserData($ucode)
	{
		$query_result = $this->db->sql_query("SELECT T0.id AS 'id', T0.Ucode AS 'ucode', T0.Uname AS 'uname', T0.Email AS 'email',
									case when T0.Superu = 'Y' then true else false end as 'superu', 
									case when T0.Locked = 'Y' then true else false end as 'locked',
									T0.wrongLogin AS 'wronglogin',
									T0.Pwd AS 'pwd', T0.UpdatePwd AS 'updatepwd', DATEDIFF(CURDATE(), T0.UpdatePwd) AS 'pw_age_day',
									case when T0.IsDefPW = 'Y' then true else false end as 'isdefpw',
									T0.CreateDate AS 'createdate', T0.CreateUcode AS 'createucode', 
									T0.UpdateDate AS 'updatedate', T0.UpdateUcode AS 'updateucode'
							FROM users T0 WHERE LOWER(T0.Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin", [':ucode' => $ucode]);
		if (!$query_result) {
			return false;
		}

		return $query_result->fetchAll();
	}

	/** count wrong logins */
	public function setWrongLogin($ucode)
	{
		$query_result = $this->db->sql_query('UPDATE users SET wrongLogin = wrongLogin + 1 WHERE LOWER(Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin', [':ucode' => $ucode]);
		if (!$query_result) {
			return false;
		}
		return $query_result;
	}

	/** reset wrong login */
	public function setGoodLogin($ucode)
	{
		$query_result = $this->db->sql_query('UPDATE users SET wrongLogin = 0 WHERE LOWER(Ucode) collate utf8mb4_bin = LOWER(:ucode) collate utf8mb4_bin', [':ucode' => $ucode]);
		if (!$query_result) {
			return false;
		}
		return $query_result;
	}
}
