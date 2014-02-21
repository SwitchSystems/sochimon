<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CountriesController extends AbstractActionController
{
	public function indexAction()
	{
		$data = $this->getServiceLocator()->get('DataGrabber');
		/*@var $data \Sochimon\Service\DataGrabber */
		$countries = $data->getCountries();
		
		return new ViewModel([
			'countries' => $countries
		]);
	}
}