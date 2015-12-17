<?php

class ContactRequest extends Record
{
	protected $userIdTo,
			  $userIdFrom,
			  $contactGroupId,
			  $time;
			  
	public function isValid()
	{
		$isValid = true;
		if(
			empty($this->userIdTo) 			||
			empty($this->userIdFrom) 		||
			empty($this->contactGroupId) 	
		)
			$isValid = false;

		return $isValid;
	}
	
	/**
	 * @return the $userIdTo
	 */
	public function getUserIdTo() {
		return $this->userIdTo;
	}

	/**
	 * @return the $userIdFrom
	 */
	public function getUserIdFrom() {
		return $this->userIdFrom;
	}

	/**
	 * @return the $contactGroupId
	 */
	public function getContactGroupId() {
		return $this->contactGroupId;
	}

	/**
	 * @return the $time
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @param field_type $userIdTo
	 */
	public function setUserIdTo($userIdTo) {
		$this->userIdTo = $userIdTo;
	}

	/**
	 * @param field_type $userIdFrom
	 */
	public function setUserIdFrom($userIdFrom) {
		$this->userIdFrom = $userIdFrom;
	}

	/**
	 * @param field_type $contactGroupId
	 */
	public function setContactGroupId($contactGroupId) {
		$this->contactGroupId = $contactGroupId;
	}

	/**
	 * @param field_type $time
	 */
	public function setTime($time) {
		$this->time = $time;
	}

}

?>