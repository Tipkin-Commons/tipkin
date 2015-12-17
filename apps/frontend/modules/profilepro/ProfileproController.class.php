<?php

class ProfileProController extends BackController
{
	protected $_user;
	protected $_profilePro;
	protected $_address;
	
	protected $_userDir;
	
	protected $_userManager;
	protected $_profileProManager;
	protected $_addressManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		$this->init();
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$isMailVerified = $this->_user->getIsMailVerified();
		
		if(is_null($this->_profilePro))
		{
			$this->app->httpResponse()->redirect('/profile-pro' . '/create');
			exit();
		}
		
		$this->page->smarty()->assign('profilePro', $this->_profilePro);
		$this->page->smarty()->assign('mainAddress', $this->_address);
		$this->page->smarty()->assign('isMailVerified', $isMailVerified);
	}
	
	public function executeCreate(HTTPRequest $request)
	{
		$this->_address =  new Address();
		
		if(!is_null($this->_profilePro))
		{
			$this->app->httpResponse()->redirect('/profile-pro');
			exit();
		}
	
		$this->_profilePro = new ProfilePro();
		
		$this->page->smarty()->assign('profilePro', $this->_profilePro);
		$this->page->smarty()->assign('mainAddress', $this->_address);
			
		if($request->postExists('save-profile'))
		{
			$this->parseForm($request, $this->_profilePro, $this->_address);
			
			$this->_addressManager->save($this->_address);
			
			$this->_profilePro->setMainAddressId($this->_address->id());
			
			$this->_profileProManager->save($this->_profilePro);
			 
			mkdir($_SERVER['DOCUMENT_ROOT'] . $this->_userDir);
			chmod($_SERVER['DOCUMENT_ROOT'] . $this->_userDir, 0755);
			
			$this->app->user()->setFlash('profil-created');
			
			$this->app->httpResponse()->redirect('/profile-pro');
		}
		
	}
	
	public function executeEdit(HTTPRequest $request)
	{
		if(is_null($this->_profilePro))
		{
			$this->app->httpResponse()->redirect('/profile-pro');
			exit();
		}
		
		if($request->postExists('save-profile'))
		{
			$this->parseForm($request, $this->_profilePro, $this->_address);
			
			$this->_addressManager->save($this->_address);
			
			$this->_profilePro->setMainAddressId($this->_address->id());
			
			$this->_profileProManager->save($this->_profilePro);
			
			$this->app->user()->setFlash('profil-updated');
			
			$this->app->httpResponse()->redirect('/profile-pro');
		}
		
		$this->page->smarty()->assign('profilePro', $this->_profilePro);
		$this->page->smarty()->assign('mainAddress', $this->_address);
	}
	
	public function executeUpdatePassword(HTTPRequest $request)
	{
		if($request->postExists('save-new-password'))
		{
			$oldPassword = htmlspecialchars($request->postData('old-password'));
			$newPassword = htmlspecialchars($request->postData('new-password'));
			$confirmNewPassword = htmlspecialchars($request->postData('confirm-new-password'));
			
			if($oldPassword == $this->_user->getPassword())
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
			
			$this->app->httpResponse()->redirect('/profile-pro');
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
			
			$this->app->httpResponse()->redirect('/profile-pro');
		}
	}
	
	public function executeValidEmail(HTTPRequest $request)
	{
		if($this->_user->getIsMailVerified())
		{
			$this->app->httpResponse()->redirect('/profile-pro');
			exit();
		}
		
		$activationKey = mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
		$this->_user->setActivationKey($activationKey);
		$this->_userManager->save($this->_user);
		
		$mailMessage = new Mail();
		$mailMessage->sendVerifyEmail($this->_user);
		
		$this->app->user()->setFlash('verification-mail-sent');
		$this->app->httpResponse()->redirect('/profile-pro');
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		if($request->postExists('confirm'))
		{
			$messageMail = new Mail();
			$messageMail->sendDisableAccount($this->_user, Tipkin\Config::get('admin-mail'));
			
			$this->app->user()->setFlash('disable-account');
			$this->app->httpResponse()->redirect('/profile-pro');
		}
	}
	
	public function executeAvatar(HTTPRequest $request)
	{
		ini_set("memory_limit",'256M');
		
		$this->page->smarty()->assign('avatar', $this->_profilePro->getAvatar());
		
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
					
					if($this->_profilePro->getAvatar() != ProfilePro::AVATAR_DEFAULT_PRO)
						unlink($_SERVER['DOCUMENT_ROOT'] . $this->_profilePro->getAvatar());
					
					$this->_profilePro->setAvatar($this->_userDir . $filename);
					$this->_profileProManager->save($this->_profilePro);
					
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
			$this->app->httpResponse()->redirect('/profile-pro');
		}
	}
	
	
	
	//Private Functions
	
	private function parseForm(HTTPRequest $request, ProfilePro $profilePro, Address $address)
	{
		//PROFILE
		$companyName	= htmlspecialchars($request->postData('company-name'));
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
		
		$profilePro->setCompanyName($companyName);
		$profilePro->setLastname($lastname);
		$profilePro->setFirstname($firstname);
		$profilePro->setDescription($description);
		$profilePro->setPhone($phone);
		$profilePro->setMobilePhone($mobilePhone);
		$profilePro->setOfficePhone($officePhone);
		$profilePro->setWebsite($website);
		$profilePro->setUserId($this->app->user()->getAttribute('id'));
		
		$address->setAddress1($address1);
		$address->setAddress2($address2);
		$address->setZipCode($zipCode);
		$address->setCity($city);
		$address->setCountry($country);
		$address->setTitle($companyName);
		$address->setUserId($this->app->user()->getAttribute('id'));
	}
	
	private function authenticationRedirection()
    {
    	if(!$this->app->user()->isAuthenticated() && $this->app->httpRequest()->cookieExists('tipkin-id'))
        {
        	$this->app->user()->setAttribute('id', $this->app->httpRequest()->cookieData('tipkin-id')) ;
        	$this->app->user()->setAuthenticated(true);
        }
        	
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
    }
	
	private function init()
	{
		//Initialisation des managers
		$this->_userManager 	= $this->managers->getManagerOf('users');
		$this->_profileProManager 	= $this->managers->getManagerOf('profilespro');
		$this->_addressManager 	= $this->managers->getManagerOf('addresses');
		
		$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_userManager->get($userId);
		
		$this->_userDir	= Users::USERS_DIRECTORY . $this->_user->id() . '/';
		
		if($this->_user->getRoleId() != Role::ROLE_MEMBER_PRO)
		{
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
		
		//Récupération du profil
		$this->_profilePro = $this->_profileProManager->getByUserId($userId);
		
		if(!is_null($this->_profilePro))
			$this->_address = $this->_addressManager->get($this->_profilePro->getMainAddressId());
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
}

?>
