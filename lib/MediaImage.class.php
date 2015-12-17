<?php

class MediaImage 
{
	public static function createAnnounceDirectory(Announcement $announce)
	{
		$directory = self::getAnnounceDirectory($announce); 
		
		if(is_null($directory))
		{
			$directory = $_SERVER['DOCUMENT_ROOT'] . Announcement::ANNOUNCEMENT_DIRECTORY . $announce->id();
			
			mkdir($directory);
			chmod($directory, 0755);
		}
	}
	
	public static function getAnnounceDirectory(Announcement $announce)
	{
		$directory = $_SERVER['DOCUMENT_ROOT'] . Announcement::ANNOUNCEMENT_DIRECTORY . $announce->id(); 
		
		if(is_dir($directory))
			return $directory; 
			
		return null;
	}

}

?>