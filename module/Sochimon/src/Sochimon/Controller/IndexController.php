<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

	public function indexAction()
	{

	

		$countries = $this->forward()->dispatch('Sochimon\Controller\CountriesController',['action' => 'index'])->setTerminal(true);
		
		
		$viewRender = $this->getServiceLocator()->get('ViewRenderer');

		return new ViewModel([
				'countriesHTML' => $viewRender->render($countries),
		]);
	}
	
	public function splashAction()
	{
		return new ViewModel();
	}

}