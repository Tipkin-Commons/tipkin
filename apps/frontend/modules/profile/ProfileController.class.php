<?php

class ProfileController extends BackController
{
	protected $_user;
	protected $_profile;
	protected $_address;
	
	protected $_userDir;
	
	protected $_userManager;
	protected $_profileManager;
	protected $_addressManager;
	
	protected $_alternateCurrencyManager;
	protected $_alternateCurrencyPostalCodeManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$profilExist = 'true';
		$isMailVerified = $this->_user->getIsMailVerified();
		
		if(is_null($this->_profile))
		{
			$profilExist = 'false';
		}
		else
		{
			//On récupère le code postal de la personne
			$postalCode = $this->_address->getZipCode();
			
			//On récupère la liste des monnaies alternatives avec ce code postal
			$alternateCurrencyPostalCodeList = $this->_alternateCurrencyPostalCodeManager->getListByPostalCode($postalCode);
			
			//On test si l'utilisateur peut utiliser des monnaie alternative
			$canUseAlternateCurrency = count($alternateCurrencyPostalCodeList) > 0;
			
			//On crée un tableau pour récupérer la liste de nos monnaies alternative utilisable par cet utilisateur
			$listAlternateCurrenciesAvailable  = array();
			
			//Si l'utilisteur peut utiliser des monnaies alternative
			if($canUseAlternateCurrency){
				//Pour chaque code postaux associé à une monnaie
				foreach($alternateCurrencyPostalCodeList as $alternateCurrencyPostalCode){
					//On ajoute l'entrée à notre tableau de liste de monnaie
					$listAlternateCurrenciesAvailable[] = $this->_alternateCurrencyManager->get($alternateCurrencyPostalCode->getAlternateCurrencyId());
				}
			}
			
			$listCurrencyUsed = explode(',', $this->_profile->getAlternateCurrenciesUsed());
			
			$this->page->smarty()->assign('alternateCurrencyManager', $this->_alternateCurrencyManager);
			$this->page->smarty()->assign('listCurrencyUsed', $listCurrencyUsed);
			$this->page->smarty()->assign('canUseAlternateCurrency', $canUseAlternateCurrency);
			$this->page->smarty()->assign('listAlternateCurrenciesAvailable', $listAlternateCurrenciesAvailable);
			
			$this->page->smarty()->assign('profile', $this->_profile);
			$this->page->smarty()->assign('mainAddress', $this->_address);
		}
		
		$this->page->smarty()->assign('profilExist', $profilExist);
		$this->page->smarty()->assign('isMailVerified', $isMailVerified);
	}
	
	public function executeCreate(HTTPRequest $request)
	{
		$this->_address =  new Address();
		
		if(!is_null($this->_profile))
		{
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
	
		$this->_profile = new Profile();
		
		$this->page->smarty()->assign('profile', $this->_profile);
		$this->page->smarty()->assign('mainAddress', $this->_address);
			
		if($request->postExists('save-profile'))
		{
			$this->parseForm($request, $this->_profile, $this->_address);
			
			$this->_addressManager->save($this->_address);
			
			$this->_profile->setMainAddressId($this->_address->id());
			
			$this->_profileManager->save($this->_profile);
			 
			mkdir($_SERVER['DOCUMENT_ROOT'] . $this->_userDir);
			chmod($_SERVER['DOCUMENT_ROOT'] . $this->_userDir, 0755);
			
			$this->app->user()->setFlash('profil-created');
			
			$this->app->httpResponse()->redirect('/profile');
		}
		
	}
	
	public function executeEdit(HTTPRequest $request)
	{
		if(is_null($this->_profile))
		{
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
		
		if($request->postExists('save-profile'))
		{
			$this->parseForm($request, $this->_profile, $this->_address);
			
			$this->_addressManager->save($this->_address);
			
			$this->_profile->setMainAddressId($this->_address->id());
			
			$this->_profileManager->save($this->_profile);
			
			$this->app->user()->setFlash('profil-updated');
			
			$this->app->httpResponse()->redirect('/profile');
		}
		
		$this->page->smarty()->assign('profile', $this->_profile);
		$this->page->smarty()->assign('mainAddress', $this->_address);
	}
	
	public function executeUpdatePassword(HTTPRequest $request)
	{
		if($request->postExists('save-new-password'))
		{
			$oldPassword = htmlspecialchars($request->postData('old-password'));
			$newPassword = htmlspecialchars($request->postData('new-password'));
			$confirmNewPassword = htmlspecialchars($request->postData('confirm-new-password'));
			
			if(Users::cryptPassword($oldPassword, Tipkin\Config::get('secret-key'))  == $this->_user->getPassword())
			{
				if($newPassword == $confirmNewPassword)
				{
					$this->_user->setPassword($newPassword, Tipkin\Config::get('secret-key'));
					$this->_userManager->save($this->_user);
					
					$this->app->user()->setFlash('password-changed');
				}
				else
				{
					$this->app->user()->setFlash('no-match-password');
				}
			}
			else
			{
				$this->app->user()->setFlash('bad-password');
			}
			
			$this->app->httpResponse()->redirect('/profile');
		}
	}
	
	public function executeUpdateMail(HTTPRequest $request)
	{
		$this->page->smarty()->assign('mail', $this->_user->getMail());
		
		if($request->postExists('save-new-mail'))
		{
			$newMail = htmlspecialchars($request->postData('new-mail'));
			$confirmNewMail = htmlspecialchars($request->postData('confirm-new-mail'));
			
			if($newMail == $confirmNewMail)
			{
				$userWithSameMail = $this->_userManager->getByMail($newMail);
				
				if(is_null($userWithSameMail))
				{
					$this->_user->setMail($newMail);
					$this->_user->setIsMailVerified(true);
					$this->_userManager->save($this->_user);
					
					$this->app->user()->setFlash('mail-changed');
				}
				else
				{
					$this->app->user()->setFlash('mail-exist');
				}
			}
			else
			{
				$this->app->user()->setFlash('no-match-mail');
			}
			
			$this->app->httpResponse()->redirect('/profile');
		}
	}
	
	public function executeValidEmail(HTTPRequest $request)
	{
		if($this->_user->getIsMailVerified())
		{
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
		
		$activationKey = mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
		$this->_user->setActivationKey($activationKey);
		$this->_userManager->save($this->_user);
		
		$mailMessage = new Mail();
		$mailMessage->sendVerifyEmail($this->_user);
		
		$this->app->user()->setFlash('verification-mail-sent');
		$this->app->httpResponse()->redirect('/profile');
	}
	public function executeUpdateMailing(HTTPRequest $request)
	{
		$this->page->smarty()->assign('mailingState', $this->_user->getMailingState());
		
		if($request->postExists('save-new-mailing-state'))
		{
			$newMailingState = $request->postData('f_mailingState');
                        if ($newMailingState==1){
                            $this->_user->setMailingState(1);
                            $this->app->user()->setFlash('mailing-state-true');
                        } else {
                            $this->_user->setMailingState(0);
                            $this->app->user()->setFlash('mailing-state-false');
                        }
                        $this->_userManager->save($this->_user);
                        $this->app->httpResponse()->redirect('/profile');
		}
	}
	
	
	public function executeDelete(HTTPRequest $request)
	{
		$this->init();
		
		if($request->postExists('confirm'))
		{
			$messageMail = new Mail();
			$messageMail->sendDisableAccount($this->_user, Tipkin\Config::get('admin-mail'));
			
			$this->app->user()->setFlash('disable-account');
			$this->app->httpResponse()->redirect('/profile');
		}
	}
	
	public function executeAvatar(HTTPRequest $request)
	{
		ini_set("memory_limit",'256M');
		
		$this->page->smarty()->assign('avatar', $this->_profile->getAvatar());
		
		if($request->fileExists('avatar'))
		{
			$avatar = $request->fileData('avatar');
			
			if($avatar['error'] == 0)
			{
				$simpleImage = new SimpleImage();
				$simpleImage->load($avatar['tmp_name']);
				
				if(!is_null($simpleImage->image_type))
				{
					$height = $simpleImage->getHeight();
					$width = $simpleImage->getWidth();
					if($height > $width)
						$simpleImage->resizeToHeight(150);
					else
						$simpleImage->resizeToWidth(150);
					
					$filename = time() . '.jpg';
					
					$simpleImage->save($_SERVER['DOCUMENT_ROOT'] . $this->_userDir . $filename);
					
					if($this->_profile->getAvatar() != Profile::AVATAR_DEFAULT_FEMALE && $this->_profile->getAvatar() != Profile::AVATAR_DEFAULT_MALE)
						unlink($_SERVER['DOCUMENT_ROOT'] . $this->_profile->getAvatar());
					
					$this->_profile->setAvatar($this->_userDir . $filename);
					$this->_profileManager->save($this->_profile);
					
					$this->app->user()->setFlash('avatar-updated');
				}
				else
				{
					$this->app->user()->setFlash('avatar-error');
				}
			}
			else
			{
				$this->app->user()->setFlash('avatar-error');
			}
			$this->app->httpResponse()->redirect('/profile');
		}
	}
	
	public function executeManageAlternateCurrencies(HTTPRequest $request){
		//On récupère le code postal de la personne
		$postalCode = $this->_address->getZipCode();
			
		//On récupère la liste des monnaies alternatives avec ce code postal
		$alternateCurrencyPostalCodeList = $this->_alternateCurrencyPostalCodeManager->getListByPostalCode($postalCode);
			
		//On test si l'utilisateur peut utiliser des monnaie alternative
		$canUseAlternateCurrency = count($alternateCurrencyPostalCodeList) > 0;
			
		//On crée un tableau pour récupérer la liste de nos monnaies alternative utilisable par cet utilisateur
		$listAlternateCurrenciesAvailable  = array();
			
		//Si l'utilisteur peut utiliser des monnaies alternative
		if($canUseAlternateCurrency){
			//Pour chaque code postaux associé à une monnaie
			foreach($alternateCurrencyPostalCodeList as $alternateCurrencyPostalCode){
				//On ajoute l'entrée à notre tableau de liste de monnaie
				$listAlternateCurrenciesAvailable[] = $this->_alternateCurrencyManager->get($alternateCurrencyPostalCode->getAlternateCurrencyId());
			}
		}
		else {
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('save-currencies')){
			$listAlternateCurrency = array();
			
			if($request->postExists('alternateCurrency')) {
				$listAlternateCurrency = $request->postData('alternateCurrency');
			}
			
			$this->_profile->setAlternateCurrenciesUsed(implode(',', $listAlternateCurrency));
			$this->_profileManager->save($this->_profile);
			
			$this->app->user()->setFlash('profil-updated');
			
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
			
		$listCurrencyUsed = explode(',', $this->_profile->getAlternateCurrenciesUsed());
			
		$this->page->smarty()->assign('listCurrencyUsed', $listCurrencyUsed);
		$this->page->smarty()->assign('listAlternateCurrenciesAvailable', $listAlternateCurrenciesAvailable);
	}
	
	//Private Functions
	
	private function parseForm(HTTPRequest $request, Profile $profile, Address $address)
	{
		//PROFILE
		$gender 		= htmlspecialchars($request->postData('gender'));
		$lastname 		= htmlspecialchars($request->postData('lastname'));
		$firstname 		= htmlspecialchars($request->postData('firstname'));
		$description	= htmlspecialchars($request->postData('description'));
		$phone		 	= htmlspecialchars($request->postData('phone'));
		$mobilePhone 	= htmlspecialchars($request->postData('mobile-phone'));
		$officePhone 	= htmlspecialchars($request->postData('office-phone'));
		$website	 	= htmlspecialchars($request->postData('website'));
		
		//ADDRESS
		$address1		= htmlspecialchars($request->postData('address-1'));
		$address2		= htmlspecialchars($request->postData('address-2'));
		$zipCode		= htmlspecialchars($request->postData('zip-code'));
		$city			= htmlspecialchars($request->postData('city'));
		$country		= 'France';
		
		$profile->setGender($gender);
		$profile->setLastname($lastname);
		$profile->setFirstname($firstname);
		$profile->setDescription($description);
		$profile->setPhone($phone);
		$profile->setMobilePhone($mobilePhone);
		$profile->setOfficePhone($officePhone);
		$profile->setWebsite($website);
		$profile->setUserId($this->app->user()->getAttribute('id'));
		
		$address->setAddress1($address1);
		$address->setAddress2($address2);
		$address->setZipCode($zipCode);
		$address->setCity($city);
		$address->setCountry($country);
		$address->setTitle($lastname . ' ' . $firstname);
		$address->setUserId($this->app->user()->getAttribute('id'));
	}
	
	private function authenticationRedirection()
	{
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
	}
	
	private function init()
	{
		//Initialisation des managers
		$this->_userManager 	= $this->managers->getManagerOf('users');
		$this->_profileManager 	= $this->managers->getManagerOf('profiles');
		$this->_addressManager 	= $this->managers->getManagerOf('addresses');
		
		$this->_alternateCurrencyManager 			= $this->managers->getManagerOf('alternateCurrency');
		$this->_alternateCurrencyPostalCodeManager 	= $this->managers->getManagerOf('alternateCurrencyPostalCode');
		
		
		$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_userManager->get($userId);
		
		$this->_userDir	= Users::USERS_DIRECTORY . $this->_user->id() . '/';
		
		if($this->_user->getRoleId() == Role::ROLE_MEMBER_PRO)
		{
			$this->app->httpResponse()->redirect('/profile-pro');
			exit();
		}
		
		//Récupération du profil
		$this->_profile = $this->_profileManager->getByUserId($userId);
		
		if(!is_null($this->_profile))
			$this->_address = $this->_addressManager->get($this->_profile->getMainAddressId());
	}
	
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash())
			{
				case 'profil-created':
				$message = 'Bienvenue sur votre profil !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'profil-updated':
				$message = 'Votre profil a été modifié avec succés !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'avatar-updated':
				$message = 'Votre photo a été modifié avec succés !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'avatar-error':
				$message = 'Une erreur s\'est produite pendant l\'envoie de votre image !
							<br /><br />
							Veuillez vérifier que vous avez bien envoyé un fichier au format image dont la taille ne dépasse pas 8Mo.';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'bad-password':
				$message = 'L\'ancien mot de passe que vous avez spécifié est incorrect.
							<br /><br />
							La modification de votre mot de passe ne s\'est pas effectuée.';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'no-match-password':
				$message = 'Le nouveau mot de passe et la confirmation de celui-ci ne correspondent pas.
							<br /><br />
							La modification de votre mot de passe ne s\'est pas effectuée.';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'password-changed':
				$message = 'Votre nouveau mot de passe a été mis à jour avec succès.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'no-match-mail':
				$message = 'Le nouvel email et la confirmation de celui-ci ne correspondent pas.
							<br /><br />
							La modification de votre email ne s\'est pas effectuée.';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'mail-exist':
				$message = 'Le nouvel email que vous avez choisi est déjà utilisé.
							<br /><br />
							La modification de votre email ne s\'est pas effectuée.';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'mail-changed':
				$message = 'Votre adresse email a été mise à jour.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'mail-verified':
				$message = 'Votre adresse email a été vérifiée avec succès.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'verification-mail-sent':
				$message = 'Un mail vous permettant de valider votre compte vient de vous être envoyé.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'mailing-state-true':
				$message = 'Vous êtes maintenant abonné à notre newsletter.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'mailing-state-false':
				$message = 'Vous êtes maintenant désabonné de notre newsletter.';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'disable-account':
				$message = 'Un message vient d\'être envoyé à l\'administrateur lui notifiant votre demande de suppression.';
	        	$message = MessageBox::Warning($message);
				break;
				
				default:
					;
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
	
	private function updateAnnouncementState(Announcement $announce)
	{
		$endPublicationDate = $announce->getEndPublicationDate();
		
		$now = new DateTime();
		$endDate = new DateTime($endPublicationDate);
		
		if($endDate->getTimestamp() < $now->getTimestamp() && $announce->getStateId() == AnnouncementStates::STATE_VALIDATED)
			$announce->setStateId(AnnouncementStates::STATE_ARCHIVED);
	}
}

?>
