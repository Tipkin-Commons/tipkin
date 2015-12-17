<?php

class ContactGroups 
{
	const USERS		= 1;
	const TIPPEURS 	= 2;
	const FRIENDS	= 3;
	const FAMILY	= 4;
	const NEIGHBORS	= 5;
	
	static public function getLabel($contactGroupId)
	{
		switch ($contactGroupId) 
		{
			case self::TIPPEURS :
			return 'Tippeurs';
			break;
			
			case self::FRIENDS :
			return 'Amis';
			break;
			
			case self::FAMILY :
			return 'Famille';
			break;
			
			case self::NEIGHBORS :
			return 'Voisins';
			break;
			
			default:
				;
			break;
		}
	}
	
	static public function getImageSrc($contactGroupId)
	{
		switch ($contactGroupId) 
		{
			case self::TIPPEURS :
			return '/images/tippeurs.png';
			break;
			
			case self::FRIENDS :
			return '/images/friends.png';
			break;
			
			case self::FAMILY :
			return '/images/family.png';
			break;
			
			case self::NEIGHBORS :
			return '/images/neighbors.png';
			break;
			
			default:
				;
			break;
		}
	}

	static public function getClass($contactGroupId)
	{
		switch ($contactGroupId) 
		{
			case self::TIPPEURS :
			return 'tippeurs';
			break;
			
			case self::FRIENDS :
			return 'friends';
			break;
			
			case self::FAMILY :
			return 'family';
			break;
			
			case self::NEIGHBORS :
			return 'neighbors';
			break;
			
			default:
				;
			break;
		}
	} 
}

?>