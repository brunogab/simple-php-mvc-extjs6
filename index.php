<?php
/**
 * Name: GApp
 * Namespace: GApp
 * Description: Simple PHP OOP MVC with ExtJS 6.2
 *
 * Copyright (C) 2019 Gabor Brunner <brunner.gabor.it@gmail.com>
 * Version: 1.0
 * License: GPL3
 *
 * This program comes with ABSOLUTELY NO WARRANTY;
 * This is free software, and you are welcome to redistribute it.
 *
 * EXTJS
 * https://www.sencha.com/legal/gpl/
 *
 * MVC
 * https://phpbridge.org/intro-to-php/creating_a_data_class
 */

/**
 * PSR-4 Autoloader
 * https://www.php-fig.org/psr/psr-4/
 * https://thewebtier.com/php/psr4-autoloading-php-files-using-composer/
 * https://moquet.net/blog/5-features-about-composer-php/
 */
require_once './vendor/autoload.php';

/**
 * run app.. \app\Lib\App.php
 */
(new \GApp\Lib\App(basename(__DIR__)));

/**
 * https://phptherightway.com/#code_style_guide
 * https://github.com/jupeter/clean-code-php
 */
