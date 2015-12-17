<?php

class ProfilePro extends Record 
{
	protected 	$companyName,
				$lastname,
				$firstname,
				$description,
				$phone,
				$mobilePhone,
				$officePhone,
				$website,
				$avatar,
				$userId,
				$mainAddressId;
	
	const AVATAR_DEFAULT_PRO = '/images/member-pro.png';
	
	const PHONE_INVALID = 4;
	const AVATAR_INVALID = 5;
	const USERID_INVALID = 6;
	const MAINADDRESSID_INVALID = 7;
	
	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return the $lastname
	 */
	public function getLastname() {
		return strtoupper($this->lastname);
	}

	/**
	 * @return the $firstname
	 */
	public function getFirstname() {
		return ucfirst($this->firstname);
	}

	/**
	 * @param field_type $lastname
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @param field_type $firstname
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	public function isValid()
	{
		return !(empty($this->companyName)
					|| empty($this->phone)
					|| empty($this->userId)
					|| empty($this->mainAddressId));
	}

	/**
	 * @return the $companyName
	 */
	public function getCompanyName() {
		return $this->companyName;
	}

	/**
	 * @return the $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @return the $mobilePhone
	 */
	public function getMobilePhone() {
		return $this->mobilePhone;
	}

	/**
	 * @return the $officePhone
	 */
	public function getOfficePhone() {
		return $this->officePhone;
	}

	/**
	 * @return the $website
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * @return the $avatar
	 */
	public function getAvatar() {
		if(empty($this->avatar))
			return self::AVATAR_DEFAULT_PRO;
		else
			return $this->avatar;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return the $mainAddressId
	 */
	public function getMainAddressId() {
		return $this->mainAddressId;
	}

	/**
	 * @param field_type $companyName
	 */
	public function setCompanyName($companyName) {
		$this->companyName = $companyName;
	}

	/**
	 * @param field_type $phone
	 */
	public function setPhone($phone) {
		if(empty($phone))
			$this->erreurs[] = self::PHONE_INVALID;
		else
			$this->phone = $phone;
	}

	/**
	 * @param field_type $mobilePhone
	 */
	public function setMobilePhone($mobilePhone) {
		$this->mobilePhone = $mobilePhone;
	}

	/**
	 * @param field_type $officePhone
	 */
	public function setOfficePhone($officePhone) {
		$this->officePhone = $officePhone;
	}

	/**
	 * @param field_type $website
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}

	/**
	 * @param field_type $avatar
	 */
	public function setAvatar($avatar) {
		if($avatar != self::AVATAR_DEFAULT_PRO)
			$this->avatar = $avatar;
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

	/**
	 * @param field_type $mainAddressId
	 */
	public function setMainAddressId($mainAddressId) {
		if(empty($mainAddressId))
			$this->erreurs[] = self::MAINADDRESSID_INVALID;
		else
			$this->mainAddressId = $mainAddressId;
	}
}

?>