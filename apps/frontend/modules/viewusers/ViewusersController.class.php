<?php

class ViewusersController extends BackController 
{
	protected $_announcementsManager;
	protected $_announcementsProManager;
	
	protected $_reservationsManager;
	
	protected $_profilesManager;
	protected $_profilesProManager;
	
	protected $_addressesManager;
	
	protected $_usersManager;
	
	protected $_contactsManager;
	protected $_contactRequestsManager;
	
	protected $_feedbacksManager;
	
	protected $_user;
	protected $_listOfUserAnnonces;
	
	protected $_alternateCurrencyManager;
	protected $_alternateCurrencyPostalCodeManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
    }
    
    public function executeMember(HTTPRequest $request)
    {
    	$userId 		= htmlspecialchars($request->getData('userId'));
    	$user 			= $this->_usersManager->get($userId);
    	$profile		= null;
    	if(!is_null($user))
    		$profile 		= $this->_profilesManager->getByUserId($user->id());
    	
    	if(is_null($profile))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit;
    	}
    	
    	$mainAddress 	= $this->_addressesManager->get($profile->getMainAddressId());
    	
    	$this->_listOfUserAnnonces = $this->_announcementsManager->getListOf($user->id());
    	
    	//On récupère le code postal de la personne
    	$postalCode = $mainAddress->getZipCode();
    		
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
    		
    	$listCurrencyUsed = explode(',', $profile->getAlternateCurrenciesUsed());
    		
    	$this->page->smarty()->assign('alternateCurrencyManager', $this->_alternateCurrencyManager);
    	$this->page->smarty()->assign('listCurrencyUsed', $listCurrencyUsed);
    	$this->page->smarty()->assign('canUseAlternateCurrency', $canUseAlternateCurrency);
    	$this->page->smarty()->assign('listAlternateCurrenciesAvailable', $listAlternateCurrenciesAvailable);
    	
    	$this->page->smarty()->assign('user', $user);
    	$this->page->smarty()->assign('profile', $profile);
    	$this->page->smarty()->assign('mainAddress', $mainAddress);
    	$this->page->smarty()->assign('listOfUserAnnonces', $this->_listOfUserAnnonces);
    	$this->page->smarty()->assign('contactsManager', $this->_contactsManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('addressesManager', $this->_addressesManager);
		$this->page->smarty()->assign('reservationsManager', $this->_reservationsManager);
		$this->page->smarty()->assign('feedbacksManager', $this->_feedbacksManager);
    	
    	$this->assignVars($user);
    }
    
	public function executePro(HTTPRequest $request)
    {
    	if(!$this->app->user()->isAuthenticated())
    	{
    		$this->app->httpResponse()->redirect404();
    		exit;
    	}

    	$userId 		= htmlspecialchars($request->getData('userId'));
    	$user 			= $this->_usersManager->get($userId);
    	$profile		= null;
    	if(!is_null($user))
    		$profile 		= $this->_profilesProManager->getByUserId($user->id());
    	
    	if(is_null($profile))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit;
    	}
    	
    	$mainAddress 	= $this->_addressesManager->get($profile->getMainAddressId());
    	
    	$this->_listOfUserAnnonces = $this->_announcementsProManager->getListOf($user->id());
    	
    	$this->page->smarty()->assign('user', $user);
    	$this->page->smarty()->assign('profile', $profile);
    	$this->page->smarty()->assign('mainAddress', $mainAddress);
    	$this->page->smarty()->assign('listOfUserAnnonces', $this->_listOfUserAnnonces);
    }
    
    private function assignVars(Users $user)
    {
    	$isContactRequestExist = false;
    	$isContactExist = false;
    	
    	if(!is_null($this->_user))
    	{
    		$contactRequest = new ContactRequest();
    		$contact 		= new Contact();
    		
    		$contactRequest->setUserIdFrom($this->_user->id());
    		$contactRequest->setUserIdTo($user->id());
    		$isContactRequestExist = $this->_contactRequestsManager->isContactRequestExist($contactRequest);
    		
    		$contact->setUserId1($this->_user->id());
    		$contact->setUserId2($user->id());
    		$isContactExist = $this->_contactsManager->isContactExist($contact);
    	}
    	
    	$this->page->smarty()->assign('isContactRequestExist', $isContactRequestExist);
    	$this->page->smarty()->assign('isContactExist', $isContactExist);
    }
    
	private function init()
    {
    	$this->_profilesManager			= $this->managers->getManagerOf('profiles');
		$this->_profilesProManager		= $this->managers->getManagerOf('profilespro');
		
		$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
		$this->_announcementsProManager	= $this->managers->getManagerOf('announcementspro');
		
		$this->_reservationsManager		= $this->managers->getManagerOf('announcementreservations');
		
		$this->_addressesManager		= $this->managers->getManagerOf('addresses');
		
		$this->_usersManager			= $this->managers->getManagerOf('users');
		
		$this->_contactsManager			= $this->managers->getManagerOf('contacts');
    	$this->_contactRequestsManager	= $this->managers->getManagerOf('contactrequests');
    	
    	$this->_feedbacksManager		= $this->managers->getManagerOf('feedbacks');
    	
    	$this->_alternateCurrencyManager 			= $this->managers->getManagerOf('alternateCurrency');
    	$this->_alternateCurrencyPostalCodeManager 	= $this->managers->getManagerOf('alternateCurrencyPostalCode');
    	
    	if($this->app->user()->isAuthenticated())
    	{
    		$userId = $this->app->user()->getAttribute('id');
    		$this->_user = $this->_usersManager->get($userId);
    	}
    }
}

?>