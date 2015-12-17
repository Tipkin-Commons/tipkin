<?php

abstract class ContactsManager extends Manager
{
	abstract protected function add(Contact $contact);
	
	abstract public function delete($id);
	
	public function save(Contact $contact)
	{
		if($contact->isValid())
		{
			$contact->isNew() ? $this->add($contact) : $this->modify($contact);
		}
		else
		{
			throw new RuntimeException('L\'adresse doit être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(Contact $contact);

	abstract public function getListOf($userId);
	
	abstract public function get($id);
	
	abstract public function isContactExist(Contact $contact);
	
	abstract public function isContactExistById($userId1, $userId2);
	
	abstract public function getByCouple($userId1, $userId2);
}

?>