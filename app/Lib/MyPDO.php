<?php 
namespace GApp\Lib; 

/**
 * class MyPDO
 */
final class MyPDO
{

	private $db_connect_id;
	private $query_result;
	private $msg;

	private $drivername;
	private $sqlversion;

	public function __construct($config)
	{		
		try {
			$opt  = array(
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES   => TRUE,
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci'"
			);
			$dsn = 'mysql:host='.$config['DB_HOST'].';port='.$config['DB_PORT'].';dbname='.$config['DB_NAME'].';charset='.$config['DB_CHAR'];		
			$this->db_connect_id = new \PDO($dsn, $config['DB_USER'], $config['DB_PASS'], $opt);

			$this->drivername = $this->db_connect_id->getAttribute(\PDO::ATTR_DRIVER_NAME);
			$this->sqlversion = $this->db_connect_id->getAttribute(\PDO::ATTR_SERVER_VERSION);

			return $this;
		}catch(\PDOException $e) { 
			$this->setMsg($e->getMessage());
			return false;
		}
	}
	
	public function getDriverName()
	{
		return $this->drivername;
	}
	public function getSqlVersion()
	{
		return $this->sqlversion;
	}

	public function setMsg($msg)
	{
		$this->msg = $msg;
		return true;
	}
	public function getMsg()
	{
		return $this->msg;
	}

	public function getDB()
	{
		return $this->db_connect_id;
	}

	public function sql_query($sql, $args = [])
	{
		if (!$args) {
			try {
				$this->query_result = $this->db_connect_id->query($sql);
				return $this->query_result;
			}catch(\PDOException $e) { 
				$this->setMsg($e->getMessage());
				return false;
			}
		}
		try {
			$this->query_result = $this->db_connect_id->prepare($sql);
			$this->bind_arrayValues($args);
			$this->query_result->execute();
			//$this->query_result->execute($args);
			return $this->query_result;
		}catch(\PDOException $e) { 
			$this->setMsg($e->getMessage());
			return false;
		}		
	}
	
	/**
	 * calculate data-type and binding values
	 * https://www.php.net/manual/en/pdostatement.bindvalue.php#104939
	 */
	public function bind_arrayValues($arraydata, $arraytype = false)
	{
		foreach($arraydata as $key => $value)
		{
			if($arraytype) {
				$this->query_result->bindValue($key,$value,$arraytype[$key]);
			}else {
				if(is_int($value))
					$param = \PDO::PARAM_INT;	//1
				elseif(is_bool($value))
					$param = \PDO::PARAM_BOOL;	//5
				elseif(is_null($value)) 
					$param = \PDO::PARAM_NULL;	//0
				elseif(is_string($value))
					$param = \PDO::PARAM_STR;	//2
				else
					$param = false;
				   
				if($param!==false)
					$this->query_result->bindValue($key,$value,$param);
				
			}
		}
	}
	
	/* not in use

	// a proxy to native PDO methods	
	public function __call($method, $args)
	{
		return call_user_func_array(array($this->db_connect_id, $method), $args);
	}

	function sql_fetchrow($query_id = false)
	{
		if ($query_id === false) {
			$query_id = $this->query_result;
		}

		if ($query_id) {
			$result = $query_id->fetch();
			return $result !== null ? $result : false;
		}

		return false;
	}

	function sql_fetchall_key_pair($query_id = false)
	{
		if ($query_id === false) {
			$query_id = $this->query_result;
		}

		if ($query_id) {
			$result = $query_id->fetchAll(PDO::FETCH_KEY_PAIR);
			return $result !== null ? $result : false;
		}

		return false;
	}
	*/
	
}
