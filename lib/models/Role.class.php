<?php
class Role extends Record 
{
	protected $name;
	
	const NAME_INVALID = 1;
	
	const ROLE_BLACKLIST 		= 1;
	const ROLE_MEMBER 			= 2;
	const ROLE_MEMBER_PRO 		= 3;
	const ROLE_ADMINISTRATEUR 	= 4;
	const ROLE_SUPER_ADMIN 		= 5;
	
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) 
	{
		if(empty($name))
			$this->erreurs[] = self::NAME_INVALID;
		else
			$this->name = $name;
	}
}
?>