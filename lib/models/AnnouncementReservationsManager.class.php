<?php

abstract class AnnouncementReservationsManager extends Manager 
{
	abstract protected function add(AnnouncementReservation $announcementReservation);
	
	abstract public function delete($id);
	
	abstract public function deletePassedWaitingPaiement();
	
	abstract public function deleteByAnnouncementId($announcementId);
	
	abstract public function deleteByUserId($userId);
	
	public function save(AnnouncementReservation $announcementReservation)
	{
		if($announcementReservation->isValid())
		{
			$announcementReservation->isNew() ? $this->add($announcementReservation) : $this->modify($announcementReservation);
		}
		else
		{
			throw new RuntimeException('Les indisponibilités pour l\'annonce doivent être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(AnnouncementReservation $announcementReservation);
	
	abstract public function getByAnnouncementId($announcementId);
	
	abstract public function get($id);
	
	abstract public function getByUserId($userId);
	
	abstract public function getListOfPassed();
	
	abstract public function isReservationExists(AnnouncementReservation $reservation);
	
	abstract public function getListOfPassedValidated();
	
	abstract public function getListOf();
}

?>