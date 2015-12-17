<?php

class AnnouncementReservation extends Record
{
	protected $date,
			  $dateEnd,
			  $dateOption,
			  $announcementId,
			  $userOwnerId,
			  $userSubscriberId,
			  $contactGroupId,
			  $price,
			  $stateId,
			  $keyCheck,
			  $transactionRef,
			  $createdTime,
			  $updatedTime,
			  $adminProceed;
			  
	/**
	 * @return the $updatedTime
	 */
	public function getUpdatedTime() {
		return $this->updatedTime;
	}

	/**
	 * @param field_type $updatedTime
	 */
	public function setUpdatedTime($updatedTime) {
		$this->updatedTime = $updatedTime;
	}

	/**
	 * @return the $adminProceed
	 */
	public function getAdminProceed() {
		return $this->adminProceed;
	}

	/**
	 * @param field_type $adminProceed
	 */
	public function setAdminProceed($adminProceed) {
		$this->adminProceed = $adminProceed;
	}

	/**
	 * @return the $createdTime
	 */
	public function getCreatedTime() {
		return $this->createdTime;
	}

	/**
	 * @param field_type $createdTime
	 */
	public function setCreatedTime($createdTime) {
		$this->createdTime = $createdTime;
	}

	/**
	 * @return the $dateEnd
	 */
	public function getDateEnd() {
		return $this->dateEnd;
	}

	/**
	 * @param field_type $dateEnd
	 */
	public function setDateEnd($dateEnd) {
		$this->dateEnd = $dateEnd;
	}

	/**
	 * @return the $transactionRef
	 */
	public function getTransactionRef() {
		return $this->transactionRef;
	}

	/**
	 * @param field_type $transactionRef
	 */
	public function setTransactionRef($transactionRef) {
		$this->transactionRef = $transactionRef;
	}

	/**
	 * @return the $keyCheck
	 */
	public function getKeyCheck() {
		return $this->keyCheck;
	}

	/**
	 * @param field_type $keyCheck
	 */
	public function setKeyCheck($keyCheck) {
		$this->keyCheck = $keyCheck;
	}

	/**
	 * @return the $contactGroupId
	 */
	public function getContactGroupId() {
		return $this->contactGroupId;
	}

	/**
	 * @return the $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param field_type $contactGroupId
	 */
	public function setContactGroupId($contactGroupId) {
		$this->contactGroupId = $contactGroupId;
	}

	/**
	 * @param field_type $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @return the $userOwnerId
	 */
	public function getUserOwnerId() {
		return $this->userOwnerId;
	}

	/**
	 * @return the $userSubscriberId
	 */
	public function getUserSubscriberId() {
		return $this->userSubscriberId;
	}

	/**
	 * @param field_type $userOwnerId
	 */
	public function setUserOwnerId($userOwnerId) {
		$this->userOwnerId = $userOwnerId;
	}

	/**
	 * @param field_type $userSubscriberId
	 */
	public function setUserSubscriberId($userSubscriberId) {
		$this->userSubscriberId = $userSubscriberId;
	}

	/**
	 * @return the $stateId
	 */
	public function getStateId() {
		return $this->stateId;
	}

	/**
	 * @param field_type $stateId
	 */
	public function setStateId($stateId) {
		$this->stateId = $stateId;
	}

	/**
	 * @return the $dateOption
	 */
	public function getDateOption() {
		return $this->dateOption;
	}

	/**
	 * @param field_type $dateOption
	 */
	public function setDateOption($dateOption) {
		$this->dateOption = $dateOption;
	}

	public function isValid()
	{
		$isValid = true;
		
		if
		(
			empty($this->date) 		||
			empty($this->dateOption)	||
			empty($this->announcementId)
		)
		{
			$isValid = false;
		}
		
		return $isValid;
	}
	
	/**
	 * @return the $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return the $announcementId
	 */
	public function getAnnouncementId() {
		return $this->announcementId;
	}

	/**
	 * @param field_type $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param field_type $announcementId
	 */
	public function setAnnouncementId($announcementId) {
		$this->announcementId = $announcementId;
	}

	public function getDateOptionLabel()
	{
		if(! empty($this->dateOption))
		{
			switch ($this->dateOption) 
			{
				case 'morning':
				 return 'la matinée';
				break;
				
				case 'evening':
				 return 'la soirée';
				break;
				
				case 'all-day':
				 return 'la journée entière';
				break;
				
				case 'period':
					$date = new DateTime($this->getDateEnd());
					return $date->format('d-m-Y');
				break;
				
				default:
					;
				break;
			}
		}
		return '';
	}
	
	public function getUsableKey($key){

		$hexStrKey  = substr($key, 0, 38);
		$hexFinal   = "" . substr($key, 38, 2) . "00";
    
		$cca0=ord($hexFinal); 

		if ($cca0>70 && $cca0<97) 
			$hexStrKey .= chr($cca0-23) . substr($hexFinal, 1, 1);
		else { 
			if (substr($hexFinal, 1, 1)=="M") 
				$hexStrKey .= substr($hexFinal, 0, 1) . "0"; 
			else 
				$hexStrKey .= substr($hexFinal, 0, 2);
		}


		return pack("H*", $hexStrKey);
	}
}

?>