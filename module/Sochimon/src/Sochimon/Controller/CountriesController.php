<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CountriesController extends AbstractActionController
{
	public function indexAction()
	{
		$countries = [];
		
		return new ViewModel([
			'countries' => $countries
		]);
	}
}