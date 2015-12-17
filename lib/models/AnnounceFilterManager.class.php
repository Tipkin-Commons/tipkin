<?php

abstract class AnnounceFilterManager extends Manager {
	abstract public function getAnnouncement(AnnounceFilter $announceFilter);
	
	abstract public function getAnnouncementPro(AnnounceFilter $announceFilter);
}

?>