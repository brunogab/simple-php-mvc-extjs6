<?php 
namespace GApp\Lib\Helper;

/**
 * trait Response
 */
trait ResponseTrait
{

	private function response($fn, $param='')
	{
		return $this->$fn($param);
	}

	/**
	 * error response
	 */
	private function error($text) 
	{
		if ($_SERVER['REQUEST_METHOD']==='POST') {

			return print('{"success": false, "info": "'.trim($text).'"}');

		}else{
			/** response a simple HTML error */
			$text = trim($text);
			return require ($this->config['path.views'] . "/error.php");
		}	
	}

	/**
	 * JSON true + info {"success": true, "info": "@text"}
	 */
	private function success($text='') 
	{
		return print('{"success": true, "info": "'.trim($text).'"}');
	}

	/**
	 * htmp response return require($file)
	 */
	private function html($file) 
	{
		return require ($this->config['path.views'] . "/" . $file);
	}

	/**
	 * JSON true + data {"success": true, "data": "@text"}
	 */
	private function successData($data) 
	{
		return print('{"success": true, "data": '.trim($data).'}');
	}

	/** --------- JSON TABLE ---------- */

	/**
	 * JSON total + result {"total": @nbrows, "results": @jsonresult}
	 */
	private function successTotal($param) //$nbrows=0, $jsonresult=""
	{
		return print('({"total":'.$param[0].', "results":'.trim($param[1]).'})');
	}

	/**
	 * JSON total=0 + result=empty {"total": "0", "results": ""}
	 */
	private function successTotalEmpty()
	{
		return print ('({"total":"0", "results":""})');		
	}

}
