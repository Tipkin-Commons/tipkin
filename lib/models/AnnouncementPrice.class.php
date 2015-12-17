<?php

class AnnouncementPrice extends Record 
{
	protected $halfDay,
			  $day,
			  $weekEnd,
			  $week,
			  $fortnight,
			  $isActive,
			  $announcementId,
			  $contactGroupId;
	
	/**
	 * @return the $weekEnd
	 */
	public function getWeekEnd() {
		if($this->weekEnd <= 0)
			return $this->day * 2;
		return $this->weekEnd;
	}

	/**
	 * @param field_type $weekEnd
	 */
	public function setWeekEnd($weekEnd) {
		$this->weekEnd = $weekEnd;
	}

	/**
	 * @return the $isActive
	 */
	public function getIsActive() {
		return $this->isActive;
	}

	/**
	 * @param field_type $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	public function isValid()
	{
		$isValid = true;
		if(
			empty($this->announcementId) ||
			empty($this->contactGroupId) 
		)
		{
			$isValid = false;
		}	
		return $isValid;
	}
			  
	/**
	 * @return the $halfDay
	 */
	public function getHalfDay() {
		if($this->halfDay < 0)
			return $this->halfDay * (-1);
		return $this->halfDay;
	}

	/**
	 * @return the $day
	 */
	public function getDay() {
		if($this->day < 0)
			return $this->day * (-1);
		return $this->day;
	}

	/**
	 * @return the $week
	 */
	public function getWeek() {
		if($this->week <= 0)
			return $this->getDay() * 7;
		return $this->week;
	}

	/**
	 * @return the $fortnight
	 */
	public function getFortnight() {
		if($this->fortnight <= 0)
			return $this->getDay() * 14;
		return $this->fortnight;
	}

	/**
	 * @return the $announcementId
	 */
	public function getAnnouncementId() {
		return $this->announcementId;
	}

	/**
	 * @return the $contactGroupId
	 */
	public function getContactGroupId() {
		return $this->contactGroupId;
	}

	/**
	 * @param field_type $halfDay
	 */
	public function setHalfDay($halfDay) {
		$this->halfDay = $halfDay;
	}

	/**
	 * @param field_type $day
	 */
	public function setDay($day) {
		$this->day = $day;
	}

	/**
	 * @param field_type $week
	 */
	public function setWeek($week) {
		$this->week = $week;
	}

	/**
	 * @param field_type $fortnight
	 */
	public function setFortnight($fortnight) {
		$this->fortnight = $fortnight;
	}

	/**
	 * @param field_type $announcementId
	 */
	public function setAnnouncementId($announcementId) {
		$this->announcementId = $announcementId;
	}

	/**
	 * @param field_type $contactGroupId
	 */
	public function setContactGroupId($contactGroupId) {
		$this->contactGroupId = $contactGroupId;
	}
	
	public function calculatePriceByPeriod($nbDays)
	{
		$nbWeek 		= 0;
		$nbWeekend = 0;	
		$nbFortnight 	= 0;

$nbDays_restants = $nbDays;

$Fortnight_nb = (int)($nbDays_restants/14);
$nbDays_restants = $nbDays_restants - $Fortnight_nb * 14;

$Week_nb = (int)($nbDays_restants/7);
$nbDays_restants = $nbDays_restants - $Week_nb * 7;

$Weekend_nb = (int)($nbDays_restants/2);
$nbDays_restants = $nbDays_restants - $Weekend_nb * 2;


$result = ($this->getFortnight() * $Fortnight_nb + $this->getWeek() * $Week_nb + $this->getWeekEnd() * $Weekend_nb + $this->getDay() * $nbDays_restants );
echo "<font color=white>location pour $Fortnight_nb quinzaines, $Week_nb semaines, $Weekend_nb WE, $nbDays_restants jours soit $result &euro;</font>";
		
		

		// On traite le week-end d'abord
		if($nbDays == 2)
		{
			return $this->getWeekEnd();
		}
		
		$nbFortnight = (int)($nbDays / 15);
		$nbDays = $nbDays % 15;
		
		if($nbDays >= 10)
		{
			$nbFortnight += 1;
			$nbDays = 0;
		}
		
		$nbWeek = (int)($nbDays / 7);
		$nbDays = $nbDays % 7;
		
		if($nbDays >= 5)
		{
			$nbWeek += 1;
			$nbDays = 0;
		}
#		return ($this->getFortnight() * $nbFortnight + $this->getWeek() * $nbWeek + $this->getDay() * $nbDays);
return $result;
	}
}

?>