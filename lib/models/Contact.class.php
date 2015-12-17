<?php

class Contact extends Record 
{
	protected $userId1,
			  $userId2,
			  $contactGroupId;
			  
	public function isValid()
	{
		$isValid = true;
		if(
			empty($this->userId1) 			||
			empty($this->userId2) 		||
			empty($this->contactGroupId) 	
		)
			$isValid = false;

		return $isValid;
	}
	
	/**
	 * @return the $userId1
	 */
	public function getUserId1() {
		return $this->userId1;
	}

	/**
	 * @return the $userId2
	 */
	public function getUserId2() {
		return $this->userId2;
	}

	/**
	 * @return the $contactGroupId
	 */
	public function getContactGroupId() {
		return $this->contactGroupId;
	}

	/**
	 * @param field_type $userId1
	 */
	public function setUserId1($userId1) {
		$this->userId1 = $userId1;
	}

	/**
	 * @param field_type $userId2
	 */
	public function setUserId2($userId2) {
		$this->userId2 = $userId2;
	}

	/**
	 * @param field_type $contactGroupId
	 */
	public function setContactGroupId($contactGroupId) {
		$this->contactGroupId = $contactGroupId;
	}

}

?>