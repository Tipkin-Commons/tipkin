<?php

abstract class FeedbackRequestsManager extends Manager
{
	abstract protected function add(FeedbackRequest $feedback);
	
	abstract public function delete($id);
	
	public function save(FeedbackRequest $feedback)
	{
		if($feedback->isValid())
		{
			$feedback->isNew() ? $this->add($feedback) : $this->modify($feedback);
		}
		else
		{
			throw new RuntimeException('Le feedback doit être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(FeedbackRequest $feedback);
	
	abstract public function getByUserId($userId);
	
	abstract public function getByAnnounceId($announceId);
	
	abstract public function getByReservationId($reservationId);
	
	abstract public function getByAuthorId($userId);
	
	abstract public function get($id);

}

?>