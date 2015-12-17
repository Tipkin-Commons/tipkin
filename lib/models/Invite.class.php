<?php

class Invite extends Record 
{
	public 	$emails_liste;
	public 	$commentaire;
	protected	$current_user;
	public $email_error;
	public $email_sent;
	
	const CIVILITY_INVALID = 1;
	const LASTNAME_INVALID = 2;
	const FIRSTNAME_INVALID = 3;
	const PHONE_INVALID = 4;
	const AVATAR_INVALID = 5;
	const USERID_INVALID = 6;
	const MAINADDRESSID_INVALID = 7;
	
	const AVATAR_DEFAULT_MALE 	= '/images/user_male.png';
	const AVATAR_DEFAULT_FEMALE = '/images/user_female.png';
	
	
	/**
	 * @return the $emails_liste
	 */
	public function getEmailsliste() {
		return $this->emails_liste;
	}
	
	/**
	 * @return email_liste as an array
	*/	 
	public function explodeEmailsListe()
	{
		//var_dump($this->emails_liste)."<br>";
		$this->emails_liste = array_unique(preg_split("/[\s,]+/", $this->emails_liste));
		//$this->emails_liste = preg_split("/[\s,]+/", $this->emails_liste); 
		//var_dump($this->emails_liste)."<br>";
		
	} 
	
	/**
	 * récupère le commentaire 
	 */
	public function getCommentaire()
	{
		return $this->$commentaire;
	} 
	 	 
	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return the $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param field_type $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	public function isValid()
	{
		return !(empty($this->gender)
					|| empty($this->lastname)
					|| empty($this->firstname)
					|| empty($this->phone)
					//|| empty($this->avatar)
					|| empty($this->userId)
					|| empty($this->mainAddressId));
	}

	/**
	 * @return the $lastname
	 */
	public function getLastname() {
		return strtoupper($this->lastname);
	}

	/**
	 * @return the $firsname
	 */
	public function getFirstname() {
		return ucfirst($this->firstname);
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
		{
			if($this->gender == 'M')
				return self::AVATAR_DEFAULT_MALE;
			else
				return self::AVATAR_DEFAULT_FEMALE;
		}
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
	 * @param field_type $lastname
	 */
	public function setLastname($lastname) {
		if(empty($lastname))
			$this->erreurs[] = self::LASTNAME_INVALID;
		else
			$this->lastname = $lastname;
	}

	/**
	 * @param field_type $firsname
	 */
	public function setFirstname($firstname) {
		if(empty($firstname))
			$this->erreurs[] = self::FIRSTNAME_INVALID;
		else
			$this->firstname = $firstname;
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
		if($avatar != self::AVATAR_DEFAULT_FEMALE && $avatar != self::AVATAR_DEFAULT_MALE )
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