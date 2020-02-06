<?php

namespace GApp\Controllers;

use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class HomeController
 */
final class HomeController
{
	use FnTrait, ResponseTrait;

	private $config;
	private $template;
	private $model;

	public function __construct($config, $template, $model)
	{
		$this->config = $config;
		$this->template = $template;
		$this->model = $model;
	}

	public function getView()
	{
		$this->config['viewpage'] = 'home';
		$this->template->template_render($this->config['path.views'] . '/main.php', $this->config);
	}

	public function getMenu()
	{
		$this->response('html', 'menu.php');
	}

	public function getHome()
	{
		$this->response('html', 'home.php');
	}
}
