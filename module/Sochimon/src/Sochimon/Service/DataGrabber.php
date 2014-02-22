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
	 * Get all countries (that have medals), use our cache since we mashed up several APIs!
	 *
	 * @return array of Sochimon\Model\Country , keyed by countryName
	 */
	public function getCountries() {
		$arrCountries=json_decode(file_get_contents('data/cache/countries.json'),true);

		$result = array();
		foreach ($arrCountries as $country) {
			$objCountry = new \Sochimon\Model\Country();
			foreach ($country as $k=>$v) {
				$objCountry->$k = $v;
			}
			$result[strtolower($objCountry->getName())]=$objCountry;
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

		$countries = $this->fetchAndStoreCountryData();

		$countries = $this->mergeAthleteDataToCountries($countries);

		$result = file_put_contents('data/cache/countries.json',json_encode($countries));
		if ($result===false)
			throw new RuntimeException('Unable to write to data/cache/countries.json');

		return true;

	}

	protected function fetchAndStoreCountryData() {

		$countries = $this->doSochiRequest('countries');

		if ($countries===false)
			throw new RuntimeException('Something bad happened with the sochi/coutnries API feed updater');

		$countries = json_decode($countries,true);

		$countries_geo = file_get_contents('http://restcountries.eu/rest/v1');

		// use the zend decoder, as we have some funky utf-8/encoding going on
		$countries_geo = Json::decode($countries_geo,Json::TYPE_ARRAY);

		// populate sochi country data with lat/lng coords
		foreach ($countries_geo as $geoCountry) {
			foreach ($countries['data'] as &$country) {
				if ($geoCountry['alpha3Code'] == $country['abbr']) {
					// echo 'Found LATLNG for '.$country['abbr']." ";
					$country['latlng'] = $geoCountry['latlng'];
					break;
				}
			}
		}

		// filter countries, we only need the countries that have actually got medals!
		$filteredCountries = array();
		foreach ($countries['data'] as $k=>$country) {
			if ($country['medals']['total']>0) {
				$filteredCountries[]=$country;
			}
		}

		return $filteredCountries;

	}

	protected function mergeAthleteDataToCountries(array $countries) {

		foreach($countries as &$country) {

			// we have to lowercase and then use underscores for the country name...GRR!
			$countryName = strtolower(str_replace(' ','_',$country['name']));
			// oh, and strip out any full stops.
			$countryName = str_replace('.','',$countryName);

			$athletes = $this->doSochiRequest('athletes',array('fields'=>'name,weight','country'=>$countryName));

			if ($athletes===false) {
				echo('unable to get athlete information for '.$country['name']);
			} else {

				$athletes = json_decode($athletes,true);

				if ($athletes!==false) {
					$totalWeight = 0;
					foreach ($athletes['data'] as $person) {
						$totalWeight+=$person['weight'];
					}
					$country['athletesCount'] = count($athletes['data']);
					$country['athletesWeight'] = $totalWeight;
				}
			}
			// @debugging
			// var_dump($country['name'].' = '.$country['athletesCount'].', '.$country['athletesWeight']);

		}


		return $countries;

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