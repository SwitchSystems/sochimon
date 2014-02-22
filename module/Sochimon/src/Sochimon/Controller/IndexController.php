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
	const GOLD_MEDAL = 1000;
	const SILVER_MEDAL = 500;
	const BRONZE_MEDAL = 200;
	const WEIGHT_DIVISOR = 10000;
	

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

	/*
	 * countries[]=country1&countries[]=country2&...
	 */
	public function scoreAction()
	{
		//@todo uncomment to get country data from ajax request
		if(!$this->getRequest()->isPost())
			throw new ErrorException('Data must be POSTed');
		
		$data = $this->getRequest()->getPost('countries',[]);		
		
		$countries = $this->getServiceLocator()->get('DataGrabber')->getCountries();
		
		//calculate the cumulative weight and non-cumulative distances between each country in order selected
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
				'distance' => $key > 0 ? $distance : 0,
				'weight' => $key > 0 ? $distanceWeightTravel[$key-1]['weight']+$countries[$country]->getTotalAthleteWeight() : $countries[$country]->getTotalAthleteWeight()
			];
		}
		
		//calculate score based on number of medals and athelete weight
		$score = [];
		$scoreTotal = 0;
		foreach($distanceWeightTravel as $key => $country)
		{
			$medalScore = $countries[$country['country']]->getGoldMedals()*self::GOLD_MEDAL +
						  $countries[$country['country']]->getSilverMedals()*self::SILVER_MEDAL +
						  $countries[$country['country']]->getBronzeMedals()*self::BRONZE_MEDAL;
			$travelScore = ($country['weight'] / self::WEIGHT_DIVISOR) * $country['distance'];
			
			$totalScore = $medalScore-$travelScore;

			$score[$key] = $totalScore;
			$scoreTotal += $totalScore;
		}
		
		//var_dump($distanceWeightTravel,$score);exit;
		
		return (new ViewModel(['score' => $scoreTotal]))->setTerminal(true);
	}
	
}