<?php
// include composer autoloader
$loader = require 'vendor/autoload.php';

if (!class_exists('Zend\Loader\AutoloaderFactory'))
	throw new RuntimeException('Unable to load Zend Framework 2. Run `php composer.phar install`');