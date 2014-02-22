<?php
namespace Sochimon\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use ErrorException;
use Treffynnon\Navigator\Distance;
use Treffynnon\Navigator\LatLong;
use Treffynnon\Navigator\Coordinate;
use Treffynnon\Navigator\Distance\Converter\MetreToMile;

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

	public function scoreAction()
	{
		//@todo uncomment to get country data from ajax request
		/*if(!$this->getRequest()->isPost())
			throw new ErrorException('Data must be POSTed');
		
		$data = $this->getRequest()->getPost('countries');*/
		
		$data = ['australia','canada','italy','france'];
		
		$countries = $this->getServiceLocator()->get('DataGrabber')->getCountries();
		
		$distanceWeightTravel = [];

		foreach($data as $key => $country)
		{
			if($key > 0)
			{				
				$distanceCalc = new Distance(new LatLong(new Coordinate($countries[$distanceWeightTravel[$key-1]['country']]->getLat()),new Coordinate($countries[$distanceWeightTravel[$key-1]['country']]->getLng())),
							new LatLong(new Coordinate($countries[$country]->getLat()),new Coordinate($countries[$country]->getLng())));
				
				$distance = $distanceCalc->get(null,new MetreToMile());
			}
			
			$distanceWeightTravel[$key] = [
				'country' => $country,
				'distance' => $key > 0 ? $distanceWeightTravel[$key-1]['distance']+$distance : 0,
				'weight' => $key > 0 ? $distanceWeightTravel[$key-1]['weight']+$countries[$country]->getTotalAthleteWeight() : $countries[$country]->getTotalAthleteWeight()
			];
		}
		
		var_dump($distanceWeightTravel);exit;
	}
	
}