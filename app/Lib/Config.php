<?php 
namespace GApp\Lib;

/**
 * class Config
 */
final class Config {

	private $directory;

	public function __construct($path)
	{
		$this->directory = $path;
	}

	/** read all config stuff into one assoc. array */
	public function getConfig()
	{
		$config				= $this->get('site');
		$config['app_menu']	= $this->get('menu');
		$config['datatype']	= $this->get('datatype');
		
		return $config;
	}

	/** read config file */
	public function get($file)
	{
		return require $this->directory . '/' . strtolower($file) . '.php';
	}

}
