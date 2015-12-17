<?php

abstract class AnnouncementPricesManager extends Manager 
{
	abstract protected function add(AnnouncementPrice $announcementPrice);
	
	abstract public function delete($id);
	
	abstract public function deleteByAnnouncementId($announcementId);
	
	public function save(AnnouncementPrice $announcementPrice)
	{
		if($announcementPrice->isValid())
		{
			$announcementPrice->isNew() ? $this->add($announcementPrice) : $this->modify($announcementPrice);
		}
		else
		{
			throw new RuntimeException('Les prix pour l\'annonce doivent être valide pour être enregistrée');
		}
	}
	
	abstract protected function modify(AnnouncementPrice $announcementPrice);
	
	abstract public function getByAnnouncementId($announcementId);
	
	abstract public function get($id);
}

?>