<?php
namespace Sochimon\Model;

class Country
{

	public $id;
	public $name;
	public $abbr;
	public $description;
	public $flag; // URL to FLAG

	public $first_appearance;
	public $num_appearances;

	public $medals = array();

	public $latlng = array(); // array of latlng points

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getFlag()
	{
		return $this->flag;
	}

	public function getTotalMedals()
	{
		return array_sum(array_values($this->medals));
	}

	public function getGoldMedals()
	{
		return $this->medals['gold'];
	}

	public function getSilverMedals()
	{
		return $this->medals['silver'];
	}

	public function getBronzeMedals()
	{
		return $this->medals['bronze'];
	}
}