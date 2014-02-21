<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

	public function indexAction()
	{

		$data = $this->getServiceLocator()->get('DataGrabber');
		/*@var $data \Sochimon\Service\DataGrabber */

		var_dump($data->updateCountriesCache());exit;

		return new ViewModel();
	}

	public function krisAction() {
		echo 'hi';
		return new ViewModel();
	}

}