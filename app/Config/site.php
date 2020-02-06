<?php

/**
 * general variables for app
 */
return [
	/** language code (for ex.: en, de, hu..) */
	'app.lang' => 'de',

	/** error reporting */
	'app.error.reporting' => E_ALL,

	/** minimum PHP version */
	'app.min.php.version' => '7.0',

	/** minimum MySQL version */
	'app.min.mysql.version' => '5.0',

	/** empty class value, if class empty in href (/index.php?class=) this will be loaded, default login */
	'app.emptyclass' => 'login',

	/** App name, show in home site and in <title></title> */
	'app.name' => 'Example - Tool',

	/** App version, show in home site */
	'app.vers' => '1.0',

	/** maximum number of login attempts, to disable set: 0 */
	'user.login.max.try' => 5,

	/** some config values used in app */
	'logo.loading' => './inc/images/logo_164x80.png',
	'logo.home' => './inc/images/logo_400x204.png',
	'logo.pdf' => './inc/images/logo_400x204.png',

	'path.config' => './app/Config',
	'path.models' => './app/Models',
	'path.views' => './app/Views',
	'path.controllers' => './app/Controllers',
	'path.lib' => './app/Lib',

	'path.js' => './inc/js',
	'path.css' => './inc/css',
	'path.downloads' => './inc/downloads',

	'path.extjs' => './vendor/ext-6.2.0.981',
	'path.pdf' => './vendor/fpdf181/fpdf.php',
];
