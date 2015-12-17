<?php
class Users extends Record
{
	protected 	$username,
				$password,
				$mail,
				$roleId,
				$isActive,
				$createdTime,
				$isMailVerified,
				$activationKey,
                                $mailingState;
		
	const USERNAME_INVALID = 1;
	const PASSWORD_INVALID = 2;
	const MAIL_INVALID = 3;
	const USERS_DIRECTORY = '/users/';
	
	/**
	 * @return the $createdTime
	 */
	public function getCreatedTime() {
		return $this->createdTime;
	}

	/**
	 * @param field_type $createdTime
	 */
	public function setCreatedTime($createdTime) {
		$this->createdTime = $createdTime;
	}

	/**
	 * @return the $isMailVerified
	 */
	public function getIsMailVerified() {
		return $this->isMailVerified;
	}

	/**
	 * @return the $activationKey
	 */
	public function getActivationKey() {
		return $this->activationKey;
	}

	/**
	 * @return the $isMailingActif
	 */
	public function getMailingState() {
		return $this->mailingState;
	}

	/**
	 * @param field_type $isMailVerified
	 */
	public function setIsMailVerified($isMailVerified) {
		$this->isMailVerified = $isMailVerified;
	}

	/**
	 * @param field_type $activationKey
	 */
	public function setActivationKey($activationKey) {
		$this->activationKey = $activationKey;
	}

	/**
	 * @return the $isActive
	 */
	public function getIsActive() {
		return $this->isActive;
	}

	/**
	 * @param field_type $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
	}

	/**
	 * @return the $mail
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * @param field_type $mail
	 */
	public function setMail($mail)
	{
		if(!$this->isMailValid($mail))
			$this->erreurs[] = self::MAIL_INVALID;
		else
			$this->mail = $mail;
	}

	public function setMailingState($mailingState)
	{
            $this->mailingState=$mailingState;
//            echo "MAILINGSTATE = ".$this->mailingState;"<br>";
	}

	public function isValid()
	{
		$isValid = true;
		
		if(empty($this->username) || strlen($this->username) < 6)
			$isValid = false;
		
		if(empty($this->password) || strlen($this->password) < 6)
			$isValid = false;
		
		if(!$this->isMailValid($this->mail))
			$isValid = false;
			
		return $isValid;
	}

	/**
	 * @return the $username
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Password crypt in md5 with a secret key
	 *
	 * @return string $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return the $roleId
	 */
	public function getRoleId() {
		return $this->roleId;
	}

	/**
	 *
	 *
	 * @param string $username
	 */
	public function setUsername($username)
	{
		if(empty($username) || strlen($username) < 6)
			$this->erreurs[] = self::USERNAME_INVALID;
		else
			$this->username = $username;
	}

	/**
	 * Set the password in the database
	 *
	 * @param string $password Password not crypted
	 * @param string $secretkey the secret key to crypt, if null then $password is already crypted
	 */
	public function setPassword($password, $secretkey = null)
	{
		if(empty($password) || strlen($password) < 6)
			$this->erreurs[] = self::PASSWORD_INVALID;
		elseif (!empty($secretkey))
			$this->password = self::cryptPassword($password, $secretkey);
		else
			$this->password = $password;
	}
	/**
	 * Crypt the password and return it
	 *
	 * @param string $password Password not crypted
	 * @param string $secretkey the secret key to crypt
	 */
	public static function cryptPassword($password, $secretkey) {
		return md5($secretkey . $password);
	}

	/**
	 * @param field_type $roleId
	 */
	public function setRoleId($roleId) {
		$this->roleId = $roleId;
	}
	
	public function isMailValid($mail)
	{
	   $Syntaxe='#^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$#';
	   if(preg_match($Syntaxe, $mail))
	      return true;
	   else
	     return false;
	}
	
	public static function CreateNewPassword($taille = "6")
	{
		//Listes des caractères possibles
		$cars = "azertyiopqsdfghjklmwxcvbn0123456789";
  		$password = '';
  		$long = strlen($cars);
  		
  		//Initialise le générateur de nombres aléatoires
  		srand((double)microtime()*1000000);
  		
  		for($i = 0 ; $i < $taille ; $i++)
  			$password = $password . substr($cars,rand(0,$long-1),1);
  		
  		return $password;
	}
}
?>