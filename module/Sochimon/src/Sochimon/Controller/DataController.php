<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DataController extends AbstractConsoleController
{
	public function updateAction()
	{
		$this->getConsole()->info->__invoke('Running data updater');

		$data = $this->getServiceLocator()->get('DataGrabber');
		/*@var $data \Sochimon\Service\DataGrabber */
		$data->updateCountriesCache();


	}
}