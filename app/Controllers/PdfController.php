<?php
namespace GApp\Controllers;

use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class PdfController
 */
final class PdfController
{
	use FnTrait, ResponseTrait;

	private $config;
	private $template;
	private $model;

	private $pdf;

	public function __construct($config, $template, $model)
	{
		$this->config   = $config;
		$this->template = $template;
		$this->model    = $model;

		require $this->config['path.pdf'];

		define('FPDF_FONTPATH','font/');
		$this->pdf = new \FPDF();

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

		$this->pdf->AddPage();
		$this->pdf->SetFont('Arial','B',16);
		$this->pdf->Cell(40,10,'Hello World! Created via FPDF');
		$this->pdf->Output($docname,'D');
	}

}
