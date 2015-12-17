<?php

class ViewadsController extends BackController 
{
	protected $_categoriesManager;
	protected $_regionsManager;
	protected $_departmentsManager;
	
	protected $_announcementsManager;
	protected $_announcementUnavailabilitiesManager;
	protected $_announcementsProManager;
	
	protected $_carrouselsManager;
	
	protected $_contactsManager;
	protected $_contactRequestsManager;
	
	protected $_announcementPricesManager;
	
	protected $_announcementReservationManager;
	
	protected $_profilesManager;
	protected $_profilesProManager;
	
	protected $_addressesManager;
	
	protected $_usersManager;
	
	protected $_feedbacksManager;
	
	protected $_user;
	
	protected $_listOfUserAnnonces;
	protected $_listOfCategories;
	
	protected $_alternateCurrencyManager;
	protected $_alternateCurrencyPostalCodeManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
    }
    
    public function executeMember(HTTPRequest $request)
    {
    	$announceId = htmlspecialchars($request->getData('announceId'));
    	
    	$announce = $this->_announcementsManager->get($announceId);
    	
    	if(!$this->announceIsValid($announce))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	if($this->app()->user()->isAdminAuthenticated())
    	{
    		if($request->postData('announce-id'))
    		{
    			$this->app->user()->setAuthenticated(true);
	            $this->app->user()->setAttribute('id', $request->postData('user-id'));
	            
	            $this->app->httpResponse()->redirect('/announcements/edit/' . $request->postData('announce-id'));
	            exit();
    		}
    	}
    	
    	$this->showAnnounceStateMessage($announce);
    	$this->displayInfoMessage();
    	
    	$profile 		= $this->_profilesManager->getByUserId($announce->getUserId());
    	$user 			= $this->_usersManager->get($announce->getUserId());
    	$mainAddress 	= $this->_addressesManager->get($profile->getMainAddressId());
    	
    	$listOfContacts			= $this->_contactsManager->getListOf($announce->getUserId());
    	$listOfPrices			= $this->_announcementPricesManager->getByAnnouncementId($announce->id());
    	$listOfReservations		= $this->_announcementReservationManager->getByAnnouncementId($announce->id());
    	
    	$this->_listOfUserAnnonces = $this->_announcementsManager->getListOf($announce->getUserId());
    	
    	$announcementUnavailabilities = $this->_announcementUnavailabilitiesManager->getByAnnouncementId($announce->id());
    	$dateList = array();
    	foreach ($announcementUnavailabilities as $unavailability) 
		{
			$dateList[] = $unavailability->getDate();
		}
		
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
		
		$this->assignVars($user);
		
    	$this->page->smarty()->assign('dateList'			, implode(',', $dateList));
		$this->page->smarty()->assign('unavailabilities'	, $announcementUnavailabilities);
    	$this->page->smarty()->assign('announce'			, $announce);
    	$this->page->smarty()->assign('profile'				, $profile);
    	$this->page->smarty()->assign('profilesManager'		, $this->_profilesManager);
    	$this->page->smarty()->assign('user'				, $user);
    	$this->page->smarty()->assign('categories'			, $this->_listOfCategories);
    	$this->page->smarty()->assign('mainAddress'			, $mainAddress);
    	$this->page->smarty()->assign('listOfUserAnnonces'	, $this->_listOfUserAnnonces);
    	$this->page->smarty()->assign('listOfContacts'		, $listOfContacts);
    	$this->page->smarty()->assign('listOfPrices'		, $listOfPrices);
    	$this->page->smarty()->assign('listOfReservations'	, $listOfReservations);
		$this->page->smarty()->assign('contactsManager'		, $this->_contactsManager);
		$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
		$this->page->smarty()->assign('feedbacksManager'	, $this->_feedbacksManager);
		$this->page->smarty()->assign('carrouselsManager'	, $this->_carrouselsManager);
		$this->page->smarty()->assign('departmentsManager'	, $this->_departmentsManager);
    }
    
	public function executePro(HTTPRequest $request)
    {
    	$announceId = htmlspecialchars($request->getData('announceId'));
    	
    	$announce = $this->_announcementsProManager->get($announceId);
    	
    	if(!$this->announceIsValid($announce))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	$profile 		= $this->_profilesProManager->getByUserId($announce->getUserId());
    	$user 			= $this->_usersManager->get($announce->getUserId());
    	$mainAddress 	= $this->_addressesManager->get($profile->getMainAddressId());
    	
    	$this->_listOfUserAnnonces = $this->_announcementsProManager->getListOf($announce->getUserId());
    	
    	$this->page->smarty()->assign('announce'			, $announce);
    	$this->page->smarty()->assign('profile'				, $profile);
    	$this->page->smarty()->assign('user'				, $user);
    	$this->page->smarty()->assign('categories'			, $this->_listOfCategories);
    	$this->page->smarty()->assign('mainAddress'			, $mainAddress);
    	$this->page->smarty()->assign('listOfUserAnnonces'	, $this->_listOfUserAnnonces);
    	$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    	$this->page->smarty()->assign('departmentsManager'	, $this->_departmentsManager);
    }
    
    private function announceIsValid($announce)
    {
    	$isValid = true;
    	
    	if(is_null($announce))
    		$isValid = false;
    		
    	return $isValid;
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
    	$this->_categoriesManager 		= $this->managers->getManagerOf('categories');
		$this->_regionsManager 			= $this->managers->getManagerOf('regions');
		$this->_departmentsManager 		= $this->managers->getManagerOf('departments');
		
		$this->_profilesManager			= $this->managers->getManagerOf('profiles');
		$this->_profilesProManager		= $this->managers->getManagerOf('profilespro');
		
		$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
		$this->_announcementsProManager	= $this->managers->getManagerOf('announcementspro');
		
		$this->_addressesManager		= $this->managers->getManagerOf('addresses');
		
		$this->_usersManager			= $this->managers->getManagerOf('users');
		
		$this->_carrouselsManager		= $this->managers->getManagerOf('carrousels');
		
		$this->_contactsManager			= $this->managers->getManagerOf('contacts');
		$this->_contactRequestsManager	= $this->managers->getManagerOf('contactrequests');
		
		$this->_feedbacksManager		= $this->managers->getManagerOf('feedbacks');
		
		$this->_announcementUnavailabilitiesManager 	= $this->managers->getManagerOf('announcementunavailabilities');
		$this->_announcementPricesManager 				= $this->managers->getManagerOf('announcementprices');
		$this->_announcementReservationManager			= $this->managers->getManagerOf('announcementreservations');
		
		$this->_alternateCurrencyManager 			= $this->managers->getManagerOf('alternateCurrency');
		$this->_alternateCurrencyPostalCodeManager 	= $this->managers->getManagerOf('alternateCurrencyPostalCode');
		
		$this->_listOfCategories		= $this->_categoriesManager->getListOf();
		
    	if($this->app->user()->isAuthenticated())
    	{
    		$userId = $this->app->user()->getAttribute('id');
    		$this->_user = $this->_usersManager->get($userId);
    	}
    }
    
    private function showAnnounceStateMessage(Announcement $announce)
    {
    	switch ($announce->getStateId()) {
    		case AnnouncementStates::STATE_ARCHIVED:
    		$this->app->user()->setFlash('announce-archived');
    		break;
    		
    		case AnnouncementStates::STATE_PENDING:
    		$this->app->user()->setFlash('announce-pending');
    		break;
    		
    		case AnnouncementStates::STATE_REFUSED:
    		$this->app->user()->setFlash('announce-refused');
    		break;
    		
    		case AnnouncementStates::STATE_DRAFT:
    		$this->app->user()->setFlash('announce-draft');
    		break;
    		
    		default:
    			;
    		break;
    	}
    }
    
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'announce-refused':
				$message = 'Cette annonce a été refusé par l\'équipe d\'administration !';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'announce-pending':
				$message = 'Cette annonce est en cours de validation par l\'équipe d\'administration !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'announce-archived':
				$message = 'Cette annonce est archivée !';
	        	$message = MessageBox::Warning($message);				
	        	break;
	        	
	        	case 'announce-draft':
				$message = 'Cette annonce est au statut de brouillon !';
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