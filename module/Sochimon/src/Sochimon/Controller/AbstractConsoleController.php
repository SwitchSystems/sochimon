<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Exception\RuntimeException;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Adapter\AdapterInterface as Console;

abstract class AbstractConsoleController extends AbstractActionController
{
	protected $console;

	protected function getConsole()
	{
		if($this->console instanceof Console)
			return $this->console;

		//make the console easily accessible
		$console = $this->getServiceLocator()->get('console');
		if (!$console instanceof Console)
			throw new RuntimeException('Cannot obtain console adapter. Are we running in a console?');

		//bind output helpers
		$console->error = function($message){
			$this->console->writeLine($message,Color::RED);
			exit;
		};

		$console->success = function($message){
			$this->console->writeLine($message,Color::GREEN);
		};

		$console->info = function($message){
			$this->console->writeLine($message,Color::LIGHT_BLUE);
		};

		return $this->console = $console;
	}
}