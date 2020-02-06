<?php

namespace GApp\Lib;

use \GApp\Lib\Helper\FnTrait;

/**
 * class Start
 */
final class Start
{
	use FnTrait;

	private $config;
	private $basename_dir;

	public function __construct($config, $basename_dir)
	{
		$this->config = $config;
		$this->basename_dir = $basename_dir;

		$this->up();
	}

	/**
	* check & set logic ..
	*/
	private function up()
	{
		/** set function */
		$this->set_1();

		/** set function */
		$this->set_2();

		/** check function */
		$this->check_1();

		/** check function */
		$this->check_2();
	}

	/** for more transparency, separate and private functions */
	private function set_1()
	{
		/**
		* https://phptherightway.com/#php_and_utf8
		*/
		header('Content-Type: text/html; charset=UTF-8');
		mb_internal_encoding('UTF-8');
		$utf_set = ini_set('default_charset', 'utf8');
		if (!$utf_set) {
			die('<pre>could not set default_charset to utf-8, please ensure it is set on your system!');
		}
		mb_http_output('UTF-8');

		/**
		* in mysql database: if we use utf8, in some language we need to have an accent-sensitive (AS) and case-insensitive (CI) search
		*
		* in mysql version 8.* : https://dev.mysql.com/worklog/task/?id=10818
		* in mysql version 5.* : we does not have AS and CI charset, uft8_* charset are NOT accent-sensitive
		*
		* for AS and a CI search/compare in sql WHERE
		*      we can use utf8mb4_bin or binary (binary is case-sensitive and we need case-insensitive -> so we can do all lowecase for compare)
		*
		* ==>
		*      WHERE LOWER(T0.ColName) collate utf8mb4_bin LIKE LOWER('%$variable%') collate utf8mb4_bin
		*      or
		*      WHERE LOWER(T0.ColName) collate utf8mb4_bin = LOWER(:bindparam) collate utf8mb4_bin
		*      or
		*      WHERE CONVERT( LOWER(T0.ColName) USING binary) = CONVERT( LOWER('$variable') USING binary)
		*/

		/** error reporting */
		error_reporting($this->config['app.error.reporting']);

		return;
	}

	private function set_2()
	{
		/** set session name and start */
		define('SESSIONNAME', $this->basename_dir);
		define('COOKIENAME', $this->basename_dir . '_c');

		session_name(SESSIONNAME);

		session_start();

		/** initialize variable */
		if (empty($_SESSION)) {
			$_SESSION['user_logged_in'] = false;
		}

		return;
	}

	private function check_1()
	{
		/** check php version */
		if (version_compare(PHP_VERSION, $this->config['app.min.php.version'], 'lt')) {
			die('<pre>PHP ' . $this->config['app.min.php.version'] . '+ is required. Currently installed version is: ' . phpversion());
		}

		return;
	}

	private function check_2()
	{
		/** some another checks.. */
		return;
	}

	/** public function */

	/** check msql version */
	public function verifyMySql($mypdo)
	{
		if ($mypdo->getDriverName() == 'mysql') {
			$mysqlversion = $mypdo->getSqlVersion();

			if (version_compare($mysqlversion, $this->config['app.min.mysql.version'], 'lt')) {
				die('<pre>MySQL ' . $this->config['app.min.mysql.version'] . '+ is required. Currently installed version is: ' . $mypdo->getSqlVersion()); //dev version 5.7.26
			}
		}

		return;
	}
}
