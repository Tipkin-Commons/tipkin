<?php

abstract class ContactRequestsManager extends Manager
{
	abstract protected function add(ContactRequest $contactRequest);
	
	abstract public function delete($id);
	
	public function save(ContactRequest $contactRequest)
	{
		if($contactRequest->isValid())
		{
			$contactRequest->isNew() ? $this->add($contactRequest) : $this->modify($contactRequest);
		}
		else
		{
			throw new RuntimeException('L\'adresse doit être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(ContactRequest $contactRequest);

	abstract public function getListOfFrom($userId);

	abstract public function getListOfTo($userId);
	
	abstract public function get($id);
	
	abstract public function isContactRequestExist(ContactRequest $contactRequest);
}

?>