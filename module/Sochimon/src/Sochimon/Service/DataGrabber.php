<?php
/**
 * Shitty hacky class to grab data from the KimonoAPI
 *
 * Thanks for reading!
 *
 * @author kris / @switchsystems on twitter, Switch Systems Ltd
 *
 */
namespace Sochimon\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;

class DataGrabber implements ServiceLocatorAwareInterface
{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	const API_KEY 		= 'd6edb21660b5659ef6c9b73e713fa801';
	const URL_SOCHI 	= 'http://sochi.kimonolabs.com/api/';
	const API_LIMIT 	= 9999;

	public function getAthletes() {
		return json_encode(file_get_contents('data/cache/athletes.json'));
	}

	public function getCountries() {
		return json_encode(file_get_contents('data/cache/countries.json'));
	}


	/**
	 * Updates the countries information from all APIs
	 *
	 * @throws RuntimeException
	 * @return boolean
	 */
	public function updateCountriesCache() {

		$countries = $this->doSochiRequest('countries');

		if ($countries===false)
			throw new RuntimeException('Something bad happened with the sochi/coutnries API feed updater');

		$result = file_put_contents('data/cache/countries.json',$countries);
		if ($result===false)
			throw new RuntimeException('Unable to write to data/cache/countries.json');

		return true;

	}

	/**
	 * Updates the athletes information
	 * DRY, mega-lolz :D
	 *
	 * @throws RuntimeException
	 * @return boolean
	 */
	public function updateAthletesCache() {

		$athletes = $this->doSochiRequest('athletes');

		if ($athletes===false)
			throw new RuntimeException('Something bad happened with the sochi/athletes API feed updater');

		$result = file_put_contents('data/cache/athletes.json',$athletes);
		if ($result===false)
			throw new RuntimeException('Unable to write to data/cache/athletes.json');

		return true;

	}


	/**
	 * Fetches data from the Sochi datafeed
	 *
	 * requires allow_url_fopen!
	 *
	 * @param unknown $urlPath
	 * @param array $args
	 * @return string
	 */
	protected function doSochiRequest($urlPath, array $args=array()) {

		$args['apikey'] = self::API_KEY;
		$args['limit'] = self::API_LIMIT;

		$url = self::URL_SOCHI.$urlPath;
		$x=0;
		foreach ($args as $k=>$v) {
			$url.= ($x==0 ? '?' : '&') .$k.'='.$v;
			$x++;
		}

		return file_get_contents($url);

	}


}