<?php 
namespace GApp\Controllers;

use \GApp\Lib\Helper\IndexTrait;
use \GApp\Lib\Helper\FnTrait;
use \GApp\Lib\Helper\ResponseTrait;

/**
 * class IndexController [ request -> processing data -> load needed Controller with Model ]
 */
final class IndexController 
{
	use IndexTrait, FnTrait, ResponseTrait;

	private $config;
	private $configcl;
	private $start;
	private $template;
	private $mypdo;

	private $controller;
	private $function;

	/** to run a class, we have to define that in this list, syntax -> lowercase */
	protected $allowed_class_list = array (	/** default classes */
		'login', 'home', 'pdf', 'doc', 'lang', 'userprofile',
		/** another classes */
		'example1'); 

	/** to run this class, we do not have to be logged in */
	protected $run_without_login_class_list = array ('login'); 
	

	public function __construct($config, $configcl, $start, $template)
	{

		/**
		 * $this->config, $this->template, $model($this->mypdo)
		 * will passed next to all controller in loadController()
		 */
		$this->config	= $config;
		$this->configcl	= $configcl;
		$this->start	= $start;
		$this->template = $template;

		/**
		 * default site config must been in exception list too
		 */
		if (!in_array($this->config['app.emptyclass'], $this->run_without_login_class_list)) {
			array_push($this->run_without_login_class_list, $this->config['app.emptyclass']);
		}
	}


	/**
	 * enter point from index.php
	 */
	public function indexAction()
	{
		/**
		 * get URL string's 
		 * for ex. index.php?class=home, index.php?class=userprofile&function=LISTING, index.php?class=userprofile&function=SAVE ...
		 * (function strtolower and function validate in Lib\Helper\FnTrait.php)
		 */
		$this->controller	= $this->fn('strtolower', $this->validate($_GET)->with($this->config['datatype'])->get('class') );
		$this->function		= $this->validate($_GET)->with($this->config['datatype'])->get('function');


		/** create mypdo class with db-config */
		$this->mypdo = new \GApp\Lib\MyPDO($this->configcl->get('database'));

		/** check DB, on fail we print error HTML or JSON (response lib\helper\responsetrait.php) */
		if(!$this->mypdo->getDB()) { 
			return $this->response('error', 'Connection fail: ' . $this->mypdo->getMsg());
		}

		/** now we have a database mypdo */

		/** we can do some checks of mypdo in Start-class */
		$this->start->verifyMySql($this->mypdo);

		/*
		|--------------------------------------------------------------------------
		| POST or GET then load Controller
		|--------------------------------------------------------------------------
		*/

		/**
		 * if we have class and function !both! variable in request HREF:
		 * we have an AJAX request from extjs, we need only Controller and Model (we not need to load a View)
		 * only do the request and response with JSON for extjs
		 * (this can be only POST request)
		 */
		if ( (!empty($this->controller)) and (!empty($this->function)) and ($_SERVER['REQUEST_METHOD']==='POST') ) {

			/**
			 * defined list of the allowed classes, if not in list we need error, otherwise we can not call Class:Function
			 */
			if (!in_array($this->controller, $this->allowed_class_list)) { 
				return $this->response('error', 'Controller not allowed: ' . $this->controller); 
			}					

			/**
			 * 1. if user is not logged in at this point (maybe lost session -> then do a LoginForm inline, reopen Login Window)
			 * 	this JSON response will catch:
			 * 		if request comes from extjs-Form: failure_section_Form() in (\js\common_fn.js)
			 * 		if request comes from extjs-Grid: failure_section_Grid() in (\js\common_fn.js)
			 * 
			 * 	except: run_without_login_class_list (classes in this array will be executed)
			 */ 
			if ( (!$_SESSION['user_logged_in']) && (!in_array($this->controller, $this->run_without_login_class_list)) ){ 
				return $this->response('error', 'relogin');
			}

		}elseif ($_SERVER['REQUEST_METHOD']==='GET'){

			/**
			 * in some case we must override the controller, for ex. if logout if empty if renewpassword ... 
			 */
			$this->h_index_calculateController();

			/**
			 * in this if-else statement, we never have this->function so we load always the default function=getView()
			 * every Controller has a getView() function, getView() will load (via TemplateController) always the main.php 
			 * with a given variable (this variable is the name of the directory \app\Views\{variable}\*.js )
			 */
			$this->function = 'getView';

		}else{
			return $this->response('error', 'unknown Controller or Function');
		}

		/**
		 * finally load Controller and Function
		 */
		return $this->loadController($this->controller, $this->function); 

	}
	

	/**
	 * Loader Class:Function
	 */
	public function loadController($controller, $function)
	{
		/** first letter to upper */
		$controller = $this->fn('ucfirst', $controller);

		/** model= for ex. LoginModel, HomeModel, UserprofileModel ... */
		$mod = "\GApp\Models\\{$controller}Model";

		/** controller= for ex. LoginController, HomeController, UserprofileController ...*/
		$con = "\GApp\Controllers\\{$controller}Controller";

		/** create model(mypdo) */
		$model = new $mod($this->mypdo);

		/** create controller(config, template, model) */
		$controller = new $con($this->config, $this->template, $model);

		/** function= for ex. LoginController->getView(), HomeController->getView(), UserprofileController->Save() ... */
		$method = [$controller, $function];

		if (is_callable($method)) {
			return $method();
		}else{
			return $this->response('error', 'Controller function is not callable: ' . $function);
		}
	}
	
}
