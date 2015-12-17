<?php

class Carrousel extends Record {
	protected $announceId;
	
	public function isValid()
	{
		if(empty($this->announceId))
			return false;
		return true;
	}
	
	/**
	 * @return the $announceId
	 */
	public function getAnnounceId() {
		return $this->announceId;
	}

	/**
	 * @param field_type $announceId
	 */
	public function setAnnounceId($announceId) {
		$this->announceId = $announceId;
	}

}

?>