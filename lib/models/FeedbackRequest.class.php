<?php
class FeedbackRequest extends Record
{
	protected $announceId,
			  $userOwnerId,
			  $userSubscriberId,
			  $userAuthorId,
			  $creationDate,
			  $reservationId;
	
	public function isValid()
	{
		if
		(
			empty($this->announceId) ||
			empty($this->userOwnerId) ||
			empty($this->userSubscriberId) ||
			empty($this->reservationId) ||
			empty($this->userAuthorId)
		)
			return false;
			
		return true;
	}
	
	/**
	 * @return the $reservationId
	 */
	public function getReservationId() {
		return $this->reservationId;
	}

	/**
	 * @param field_type $reservationId
	 */
	public function setReservationId($reservationId) {
		$this->reservationId = $reservationId;
	}

	/**
	 * @return the $announceId
	 */
	public function getAnnounceId() {
		return $this->announceId;
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
	 * @return the $userAuthorId
	 */
	public function getUserAuthorId() {
		return $this->userAuthorId;
	}

	/**
	 * @return the $creationDate
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @param field_type $announceId
	 */
	public function setAnnounceId($announceId) {
		$this->announceId = $announceId;
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
	 * @param field_type $userAuthorId
	 */
	public function setUserAuthorId($userAuthorId) {
		$this->userAuthorId = $userAuthorId;
	}

	/**
	 * @param field_type $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}
}