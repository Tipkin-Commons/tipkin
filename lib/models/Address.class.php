<?php

class Address extends Record 
{
	protected 	$title,
				$address1,
				$address2,
				$zipCode,
				$city,
				$country,
				$userId;
	
	const TITLE_INVALID = 1;
	const ADDRESS1_INVALID = 2;
	const ZIPCODE_INVALID = 3;
	const CITY_INVALID = 4;
	const COUNTRY_INVALID = 5;
	const USERID_INVALID = 6;
				
	public function isValid()
	{
		$isValid = true;
		
		if(empty($this->title)
			|| empty($this->address1)
			|| empty($this->zipCode)
			|| empty($this->city)
			|| empty($this->country)
			|| empty($this->userId))
		{
			$isValid = false;
		}
		
		return $isValid;
	}
	
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $address1
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * @return the $address2
	 */
	public function getAddress2() {
		return $this->address2;
	}

	/**
	 * @return the $zipCode
	 */
	public function getZipCode() {
		return $this->zipCode;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return strtoupper($this->city);
	}

	/**
	 * @return the $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) 
	{
		if(empty($title))
			$this->erreurs[] = self::TITLE_INVALID;
		else
			$this->title = $title;
	}

	/**
	 * @param field_type $address1
	 */
	public function setAddress1($address1) 
	{
		if(empty($address1))
			$this->erreurs[] = self::ADDRESS1_INVALID;
		else
			$this->address1 = $address1;
	}

	/**
	 * @param field_type $address2
	 */
	public function setAddress2($address2) {
		$this->address2 = $address2;
	}

	/**
	 * @param field_type $zipCode
	 */
	public function setZipCode($zipCode) {
		if(empty($zipCode))
			$this->erreurs[] = self::ZIPCODE_INVALID;
		else
			$this->zipCode = $zipCode;
	}

	/**
	 * @param field_type $city
	 */
	public function setCity($city) {
		if(empty($city))
			$this->erreurs[] = self::CITY_INVALID;
		else
			$this->city = $city;
	}

	/**
	 * @param field_type $country
	 */
	public function setCountry($country) {
		if(empty($country))
			$this->erreurs[] = self::COUNTRY_INVALID;
		else
			$this->country = $country;
	}

	/**
	 * @param field_type $userId
	 */
	public function setUserId($userId) {
		if(empty($userId))
			$this->erreurs[] = self::USERID_INVALID;
		else
			$this->userId = $userId;
	}
}

?>