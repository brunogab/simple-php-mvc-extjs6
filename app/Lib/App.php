<?php
namespace GApp\Lib;

/**
 * create all needed class (classes without database)
 */
final class App
{
	private $basename;

	public function __construct($basename)
	{
		$this->basename = $basename;
		$this->run();
	}

	private function run()
	{
		/** 1. create Config class with set config directory (config class will remain empty, gives only arrays back) */
		$configcl = new Config('app/Config');
		$config   = $configcl->getConfig();
		
		/** 2. create Start class with config and basname, than call up() function */
		$start = new Start($config, $this->basename);

		/** 3. create Template class with sablon \views\base.php */
		$template = new \GApp\Controllers\TemplateController($config['path.views'] . "/base.php");

		/** 4. create IndexController class and call indexAction() */
		return (new \GApp\Controllers\IndexController($config, $configcl, $start, $template))->indexAction();
		
		/**
		 * or we can create an IoC container and register all nedded class.. and pass next to indexController via DI
		 * https://code.tutsplus.com/tutorials/dependency-injection-huh--net-26903
		 */
	}

}
