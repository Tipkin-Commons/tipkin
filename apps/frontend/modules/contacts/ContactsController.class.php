<?php

class ContactsController extends BackController
{
	protected $_usersManager;
	protected $_contactsManager;
	protected $_contactRequestsManager;
	protected $_profilesManager;
	protected $_addressesManager;
	
	protected $_user;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
    
	public function executeIndex(HTTPRequest $request)
	{
		if($request->getExists('contactGroup'))
		{
			$contactGroup = $request->getData('contactGroup');
			$this->page->smarty()->assign('contactGroup', $contactGroup);
		}
		
		$contacts = $this->_contactsManager->getListOf($this->_user->id());
		$this->page->smarty()->assign('contacts', $contacts);
		
		$contactRequests = $this->_contactRequestsManager->getListOfTo($this->_user->id());
		$this->page->smarty()->assign('contactRequests', $contactRequests);
		
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('addressesManager', $this->_addressesManager);
	}
	
	public function executeAccept(HTTPRequest $request)
	{
		$contactRequestId = htmlspecialchars($request->getData('contactRequestId'));
		$contactRequest = $this->_contactRequestsManager->get($contactRequestId);
		
		$profile = $this->_profilesManager->getByUserId($contactRequest->getUserIdFrom());
		
		$this->page->smarty()->assign('contactRequest', $contactRequest);
		$this->page->smarty()->assign('profile', $profile);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		
		if($request->postExists('confirm'))
		{
			$contact = new Contact();
			$contact->setUserId1($contactRequest->getUserIdFrom());
			$contact->setUserId2($contactRequest->getUserIdTo());
			$contact->setContactGroupId($contactRequest->getContactGroupId());

			$this->_contactsManager->save($contact);
			$this->_contactRequestsManager->delete($contactRequest->id());
			
			$this->app->user()->setFlash('contact-added');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeRefuse(HTTPRequest $request)
	{
		$contactRequestId = htmlspecialchars($request->getData('contactRequestId'));
		$contactRequest = $this->_contactRequestsManager->get($contactRequestId);
		
		$profile = $this->_profilesManager->getByUserId($contactRequest->getUserIdFrom());
		
		$this->page->smarty()->assign('contactRequest', $contactRequest);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('profile', $profile);
		
		if($request->postExists('confirm'))
		{
			$this->_contactRequestsManager->delete($contactRequest->id());
			
			$this->app->user()->setFlash('contact-request-refused');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$contactId = htmlspecialchars($request->getData('contactId'));
		$contact = $this->_contactsManager->get($contactId);
		
		$userId =  null;
		if($contact->getUserId1() == $this->_user->id())
			$userId = $contact->getUserId2();
		else
			$userId = $contact->getUserId1();
			
		$profile = $this->_profilesManager->getByUserId($userId);
		
		$this->page->smarty()->assign('contact', $contact);
		$this->page->smarty()->assign('profile', $profile);
		
		if($request->postExists('confirm'))
		{
			$this->_contactsManager->delete($contact->id());
			
			$this->app->user()->setFlash('contact-deleted');
			
			$this->app->httpResponse()->redirect('/contacts');
			exit();
		}
	}
	
	public function executeAdd(HTTPRequest $request)
	{
    	
		
		$userId 		= htmlspecialchars($request->getData('userId'));
    	$user 			= $this->_usersManager->get($userId);
    	
    	$this->page->smarty()->assign('user', $user);
    	
    	if($request->postExists('contact-group'))
    	{
    		$contactRequest = new ContactRequest();
    		
    		$contactRequest->setUserIdFrom($this->_user->id());
    		$contactRequest->setUserIdTo(htmlspecialchars($request->postData('user-id-to')));
    		$contactRequest->setContactGroupId(htmlspecialchars($request->postData('contact-group')));
    		
    		$this->_contactRequestsManager->save($contactRequest);

    		//TODO envoyer un mail
    		$userFrom 	= $this->_usersManager->get($contactRequest->getUserIdFrom());
    		$userTo		= $this->_usersManager->get($contactRequest->getUserIdTo());
    		
    		$messageMail = new Mail();
    		$messageMail->sendContactRequest($userFrom, $userTo);
    		
    		$this->app->user()->setFlash('contact-request-sent');
    		$this->app->httpResponse()->redirect('/contacts');
    		
    	}
    }
    
	private function authenticationRedirection()
	{
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
	}
    
    private function init()
    {
    	$this->_usersManager			= $this->managers->getManagerOf('users');
    	$this->_contactsManager			= $this->managers->getManagerOf('contacts');
    	$this->_contactRequestsManager	= $this->managers->getManagerOf('contactrequests');
    	$this->_profilesManager			= $this->managers->getManagerOf('profiles');
    	$this->_addressesManager		= $this->managers->getManagerOf('addresses');
    	
    	$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_usersManager->get($userId);
		
    	if($this->_user->getRoleId() == Role::ROLE_MEMBER_PRO)
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if(is_null($this->_profilesManager->getByUserId($userId)))
		{
			$this->app->httpResponse()->redirect('/profile');
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
				case 'contact-request-sent':
				$message = 'Votre demande d\'ajout a été envoyée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-added':
				$message = 'Le membre a été ajouté à votre liste de contacts !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-request-refused':
				$message = 'La demande d\'ajout de contact a été supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'contact-deleted':
				$message = 'Le contact a été supprimé !';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
	
	
}

?>