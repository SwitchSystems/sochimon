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

<<<<<<< HEAD
		var_dump($data->updateCountriesCache());exit;
=======
>>>>>>> de65c2d5784ddfad5aab557eb3de45892912a2f9

		$countries = $this->forward()->dispatch('Sochimon\Controller\CountriesController',['action' => 'index'])->setTerminal(true);
		
		
		$viewRender = $this->getServiceLocator()->get('ViewRenderer');

		return new ViewModel([
				'countriesHTML' => $viewRender->render($countries),
		]);
	}

	public function krisAction() {
		echo 'hi';
		return new ViewModel();
	}

}