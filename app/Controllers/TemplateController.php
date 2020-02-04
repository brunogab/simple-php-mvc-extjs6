<?php 
namespace GApp\Controllers;

/**
 * class TemplateController
 */
class TemplateController {

	private $base_template;
	private $page;
	private $variables;


	public function __construct($base_template)
	{
		$this->base_template = $base_template;
	}

	public function template_render($page, $data = array())
	{
		foreach ($data as $key => $value) {
			$this->variables[$key] = $value;
		}

		$this->page = $page;
		require $this->base_template;
	}

	public function template_content()
	{
		require $this->page;
	}
	
}
