<?php

namespace GApp\Lib\Helper;

/**
 * helper for IndexController (functions used only in IndexController)
 */
trait IndexTrait
{
	/** calculate controller logic */
	public function h_index_calculateController()
	{
		/** if class is empty override with config value */
		if (empty($this->controller)) {
			$this->controller = $this->config['app.emptyclass'];
		}

		/** if request index.php?class=logout then clean up session and starts over again */
		if ($this->controller == 'logout') {
			$this->fn('session_destroy');
			header('Location: index.php');
			die(); /** https://www.php.net/manual/en/function.header.php#122279 */
		}

		if ($_SESSION['user_logged_in']) {
			/** at this point we are logged in */
			$this->h_index_loggedInSets();
		} else {
			/** if not logged_in and not in exception list then: the js to be loaded is view\login\*.js */

			/** do nothing with classes in exception list */
			if (!in_array($this->controller, $this->run_without_login_class_list)) {
				$this->controller = 'login';
			}
		}

		return;
	}

	/**
	 * if logged in we do some check's - set's - override's..
	 */
	private function h_index_loggedInSets()
	{
		/**
		 * 1. if href was index.php?class=login and remember at this point we are logged in => we dont want to show login again, so we override it
		 * 2. check defined list of the allowed pages, if not in list we simply load default site
		 */
		if (($this->controller == 'login') || (!in_array($this->controller, $this->allowed_class_list))) {
			$this->controller = 'home';
		}

		return;
	}
}
