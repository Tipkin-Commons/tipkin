<?php

class UsersController extends BackController
{
	protected $_userManager;
	protected $_roleManager;
	protected $_addressManager;
	protected $_profileManager;
	protected $_profileProManager;
	protected $_announcementsManager;
	protected $_announcementsProManager;
	protected $_announcementPricesManager;
	protected $_announcementUnavailabilitiesManager;
	protected $_announcementReservationsManager;
	
	protected $_admin;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$users = $this->_userManager->getListOf();
		
		$this->page->smarty()->assign('users', $users);
		$this->page->smarty()->assign('admin', $this->_admin);
		$this->page->smarty()->assign('role', $this->_roleManager);
		$this->page->smarty()->assign('profilesManager', $this->_profileManager);
		$this->page->smarty()->assign('profilesProManager', $this->_profileProManager);
		$this->page->smarty()->assign('addressesManager', $this->_addressManager);
		
	}
	
	public function executeAddMember(HTTPRequest $request)
	{
		$this->page->smarty()->assign('url', 'add-member');
		$this->page->smarty()->assign('roleName', 'Membre');
		$this->page->smarty()->assign('role', Role::ROLE_MEMBER);
		
		if($request->postExists('add-member'))
		{
			$this->parseForm($request);
		}
	}
	
	public function executeAddMemberPro(HTTPRequest $request)
	{
		$this->page->smarty()->assign('url', 'add-member-pro');
		$this->page->smarty()->assign('roleName', 'Membre Pro');
		$this->page->smarty()->assign('role', Role::ROLE_MEMBER_PRO);
		
		if($request->postExists('add-member-pro'))
		{
			$this->parseForm($request);
		}
	}
	
	public function executeAddAdmin(HTTPRequest $request)
	{
		$this->page->smarty()->assign('url', 'add-admin');
		$this->page->smarty()->assign('roleName', 'Administrateur');
		$this->page->smarty()->assign('role', Role::ROLE_ADMINISTRATEUR);
		
		if($request->postExists('add-admin'))
		{
			$this->parseForm($request);
		}
	}
	
	public function executeDisable(HTTPRequest $request)
	{
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		
		if($user->getRoleId() == Role::ROLE_MEMBER_PRO)
		{
			$listOfAnnounces = $this->_announcementsProManager->getListOf($user->id());
			foreach ($listOfAnnounces as $announce)
			{
				$announce->setIsPublished(false);
				$this->_announcementsProManager->save($announce);
			}
		}
		else
		{
			$listOfAnnounces = $this->_announcementsManager->getListOf($user->id());
			foreach ($listOfAnnounces as $announce)
			{
				if($announce->getStateId() ==  AnnouncementStates::STATE_VALIDATED)
					$announce->setStateId(AnnouncementStates::STATE_ARCHIVED);
				if($announce->getStateId() ==  AnnouncementStates::STATE_PENDING)
					$announce->setStateId(AnnouncementStates::STATE_DRAFT);
				$this->_announcementsManager->save($announce);
			}
		}
		
		$user->setIsActive(0);
		
		$this->_userManager->save($user);
		
		$messageMail = new Mail();
		if($user->getRoleId() == Role::ROLE_MEMBER_PRO)
			$messageMail->sendAccountProDisabled($user);
		else
			$messageMail->sendAccountDisabled($user);
		
		$this->app->httpResponse()->redirect('/admin/users');
	}
	
	public function executeEnable(HTTPRequest $request)
	{
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		$user->setIsActive(1);
		
		$this->_userManager->save($user);
		
		$messageMail = new Mail();
		if($user->getRoleId() == Role::ROLE_MEMBER_PRO)
			$messageMail->sendAccountProEnabled($user);
		else
			$messageMail->sendAccountEnabled($user);
		
		$this->app->httpResponse()->redirect('/admin/users');
	}
	
	public function executePromote(HTTPRequest $request)
	{
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		$user->setRoleId(Role::ROLE_ADMINISTRATEUR);
		
		$this->_userManager->save($user);
		
		$messageMail = new Mail();
		$messageMail->sendPromoteAdmin($user);
		
		$this->app->httpResponse()->redirect('/admin/users');
	}
	
	public function executeRevoque(HTTPRequest $request)
	{
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		$user->setRoleId(Role::ROLE_MEMBER);
		
		$this->_userManager->save($user);
		
		$messageMail = new Mail();
		$messageMail->sendRevoqueAdmin($user);

		$this->app->httpResponse()->redirect('/admin/users');
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$user = $this->_userManager->get($request->app()->httpRequest()->getData('userId'));
		
		$this->_addressManager->deleteByUserId($user->id());
		$this->_profileManager->deleteByUserId($user->id());
		$this->_profileProManager->deleteByUserId($user->id());
		
		$listOfAnnounce = $this->_announcementsManager->getByUserId($user->id());
		foreach ($listOfAnnounce as $announce)
		{
			$this->_announcementPricesManager->deleteByAnnouncementId($annouce->id());
			$this->_announcementUnavailabilitiesManager->deleteByAnnouncementId($annouce->id());
		}
		
		$this->_announcementsManager->deleteByUserId($user->id());
		$this->_announcementsProManager->deleteByUserId($user->id());
		$this->_announcementReservationsManager->deleteByUserId($user->id());
		$this->_userManager->delete($user->id());
		
		$userDir	= $_SERVER['DOCUMENT_ROOT'] . '/users/' . $user->id();
		if(file_exists($userDir))
			rmdir($userDir);
		
		$messageMail = new Mail();
		$messageMail->sendAccountDeletedByAdmin($user);
		
		$this->app->httpResponse()->redirect('/admin/users');
	}
	
	private function authenticationRedirection()
	{
		if(!$this->app->user()->isAdminAuthenticated())
		{
        	$this->app->httpResponse()->redirect('/admin/');
        	exit();
        }
	}
        
    private function init()
    {
        $this->_userManager 		= $this->managers->getManagerOf('users');
        $this->_roleManager 		= $this->managers->getManagerOf('roles');
        $this->_addressManager 		= $this->managers->getManagerOf('addresses');
        $this->_profileManager 		= $this->managers->getManagerOf('profiles');
        $this->_profileProManager 	= $this->managers->getManagerOf('profilespro');
        $this->_announcementsManager 					= $this->managers->getManagerOf('announcements');
        $this->_announcementsProManager 				= $this->managers->getManagerOf('announcementspro');
        $this->_announcementPricesManager 				= $this->managers->getManagerOf('announcementprices');
		$this->_announcementUnavailabilitiesManager 	= $this->managers->getManagerOf('announcementunavailabilities');
		$this->_announcementReservationsManager 		= $this->managers->getManagerOf('announcementreservations');
        
        $this->_admin = $this->_userManager->get($this->app->user()->getAttribute('admin-id'));

        if($this->_admin->getRoleId() < Role::ROLE_ADMINISTRATEUR)
        {
        	$this->app->httpResponse()->redirect('/admin/../');
        	exit();
        }
	}
	
	private function parseForm(HTTPRequest $request)
	{
		$username 				= htmlspecialchars($request->postData('username'));
		$mail 					= htmlspecialchars($request->postData('mail'));
        $mailConfirmation 		= htmlspecialchars($request->postData('mail-confirmation'));
        if($request->postExists('generate-password'))
        {
        	$password = $passwordConfirmation = Users::CreateNewPassword();
        }
        else
        {
        	$password 				= htmlspecialchars($request->postData('password'));
        	$passwordConfirmation	= htmlspecialchars($request->postData('password-confirmation'));
        }
        $role					= htmlspecialchars($request->postData('role'));
        
		if($mail == $mailConfirmation
   			&& $password == $passwordConfirmation
  			&& strlen($username) >= 6
   			&& strlen($password) >= 6)
   		{
   			$user = new Users();
   			$user->setUsername($username);
   			$user->setMail($mail);
   			$user->setPassword($password, Tipkin\Config::get('secret-key'));
   			$user->setRoleId($role);
        			
   			if(!$this->_userManager->isUsernameOrMailExist($username, $mail))
   			{
   				$this->_userManager->save($user);
   				
   				$messageMail = new Mail();
				$messageMail->sendRegistrationInfo($user, $password);
  				
   				$this->app->user()->setFlash('new-user-added');
   				
  				$this->app->httpResponse()->redirect('/admin/users');
  				exit();
  			}
  			else
  			{
   				$this->app->user()->setFlash('username-or-mail-exist');
   				
   				$this->app->httpResponse()->redirect('/admin/users');
   				exit();
   			}
   		}
   		else
   		{
   			$this->app->user()->setFlash('form-invalid');
   			
   			$this->app->httpResponse()->redirect('/admin/users');
   			exit();
   		}
	}
	
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash())
			{
				case 'test':
				$message = 'test !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'new-user-added':
				$message = 'Le nouvel utilisateur a été ajouté avec succès !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'username-or-mail-exist':
				$message = 'Le nom d\'utilisateur ou l\'adresse mail que vous avez entré existe déjà !';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'form-invalid':
				$message = 'Le formulaire n\'a pas pu être validé  !
			   				<br /><br />
			   				Certains champs sont incorrects. Veuillez activez le javascript pour plus d\'informations.';
	        	$message = MessageBox::Error($message);
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