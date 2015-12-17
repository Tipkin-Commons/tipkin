<?php

class WebsiteOpinion extends Record {
	protected $username,
			  $comment,
			  $creationDate,
			  $isPublished;
	
	public function isValid()
	{
		if(
			empty($this->username) ||
			empty($this->comment)
		)
			return false;
		return true;
	}
	
	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return the $comment
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @return the $creationDate
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @return the $isPublished
	 */
	public function getIsPublished() {
		return $this->isPublished;
	}

	/**
	 * @param field_type $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @param field_type $comment
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * @param field_type $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param field_type $isPublished
	 */
	public function setIsPublished($isPublished) {
		$this->isPublished = $isPublished;
	}


}

?>