<?php
namespace GApp\Controllers;

use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class DocController
 */
final class DocController
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
		if (!empty($_GET['docname'])) {
			$this->getDoc();
		}else {
			$this->config['viewpage'] = 'home';
			$this->template->template_render($this->config['path.views'] . "/main.php", $this->config);
		}
	}

	public function getDoc()
	{
		$docname = $this->validate($_GET)->with('string')->get('docname');

		$file = $this->config['path.downloads'] . "/" . $docname;

		/** https://www.php.net/manual/en/function.readfile.php */
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			die();
		}
	}

}
