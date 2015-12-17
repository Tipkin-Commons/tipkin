<?php

abstract class AnnouncementUnavailabilitiesManager extends Manager 
{
	abstract protected function add(AnnouncementUnavailability $announcementUnavailability);
	
	abstract public function delete($id);
	
	abstract public function deleteByAnnouncementId($announcementId);
	
	public function save(AnnouncementUnavailability $announcementUnavailability)
	{
		if($announcementUnavailability->isValid())
		{
			$announcementUnavailability->isNew() ? $this->add($announcementUnavailability) : $this->modify($announcementUnavailability);
		}
		else
		{
			throw new RuntimeException('Les indisponibilités pour l\'annonce doivent être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(AnnouncementUnavailability $announcementUnavailability);
	
	abstract public function getByAnnouncementId($announcementId);
	
	abstract public function get($id);
}

?>