<?php

class Moderate extends Record 
{
	const TYPE_ANNOUNCEMENT = 1;
	const TYPE_FEEDBACK 	= 2;
	
	protected 	$userAuthorId,
				$type,
				$typeId,
				$message;

	public function isValid()
	{
		if(
			empty($this->userAuthorId) 	||
			empty($this->type) 			||
			empty($this->typeId) 		||
			empty($this->message)
		)
			return false;
		return true;
	}
	
	/**
	 * @return the $userAuthorId
	 */
	public function getUserAuthorId() {
		return $this->userAuthorId;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $typeId
	 */
	public function getTypeId() {
		return $this->typeId;
	}

	/**
	 * @return the $message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param field_type $userAuthorId
	 */
	public function setUserAuthorId($userAuthorId) {
		$this->userAuthorId = $userAuthorId;
	}

	/**
	 * @param field_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param field_type $typeId
	 */
	public function setTypeId($typeId) {
		$this->typeId = $typeId;
	}

	/**
	 * @param field_type $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	
}

?>