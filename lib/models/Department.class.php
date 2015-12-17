<?php

class Department extends Record
{
	protected $regionId,
			  $name;
	
	/**
	 * @return the $regionId
	 */
	public function getRegionId() {
		return $this->regionId;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $regionId
	 */
	public function setRegionId($regionId) {
		$this->regionId = $regionId;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	
}

?>