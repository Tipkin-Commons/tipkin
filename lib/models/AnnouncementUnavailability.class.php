<?php

class AnnouncementUnavailability extends Record
{
	protected $date,
			  $dateOption,
			  $announcementId;
			  
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

}

?>