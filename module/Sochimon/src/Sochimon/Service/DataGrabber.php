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
use Zend\Json\Json;

class DataGrabber implements ServiceLocatorAwareInterface
{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	const API_KEY 		= 'd6edb21660b5659ef6c9b73e713fa801';
	const URL_SOCHI 	= 'http://sochi.kimonolabs.com/api/';
	const API_LIMIT 	= 9999;


	/**
	 * Get all athletes
	 *
	 * @return mixed
	 */
	public function getAthletes() {

		return json_decode(file_get_contents('data/cache/athletes.json'));
	}

	/**
	 * Get all countries
	 *
	 * @return mixed
	 */
	public function getCountries() {
		$countries=json_decode(file_get_contents('data/cache/countries.json'),true);

		$result = array();

		foreach ($countries['data'] as $country) {
			$objCountry = new Sochimoon\Model\Country();
			foreach ($country as $k=>$v) {
				$objCountry->$k = $v;
			}
			$result[]=$objCountry;
		}

		return $result;

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

		$countries = json_decode($countries,true);

		$countries_geo = file_get_contents('http://restcountries.eu/rest/v1');

		try {
		// use the zend decoder, as we have some funky utf-8/encoding going on
		$countries_geo = Json::decode($countries_geo,Json::TYPE_ARRAY);
		} catch (\Exception $e) {
			var_dump($e->getMessage());
		}

		// populate sochi country data with lat/lng coords
		foreach ($countries_geo as $geoCountry) {
			foreach ($countries['data'] as &$country) {
				if ($geoCountry['alpha3Code'] == $country['abbr']) {
					echo 'Found LATLNG for '.$country['abbr']." ";
					$country['latlng'] = $geoCountry['latlng'];
					break;
				}
			}
		}

		$result = file_put_contents('data/cache/countries.json',json_encode($countries));
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