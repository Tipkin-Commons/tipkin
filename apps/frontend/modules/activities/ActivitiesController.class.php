<?php

class ActivitiesController extends BackController
{
	//$activationKey = mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
	protected $_usersManager;
	
	protected $_announcementReservationManager;
	protected $_announcementReservation;
	
	protected $_addressesManager;
	
	protected $_contactsManager;
	protected $_announcementPricesManager;
	
	protected $_announcementManager;
	protected $_profilesManager;
	
	protected $_listOfRerservations;
	
	protected $_alternateCurrencyManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    } 
    
	public function executeLocations(HTTPRequest $request)
    {
    	$this->page->smarty()->assign('listOfReservations'	, $this->_listOfRerservations);
    	$this->page->smarty()->assign('announcementManager'	, $this->_announcementManager);
		$this->page->smarty()->assign('addressesManager'	, $this->_addressesManager);
    	$this->page->smarty()->assign('profilesManager'		, $this->_profilesManager);
    	$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    }
    
    public function executeReservations(HTTPRequest $request)
    {
    	$this->page->smarty()->assign('listOfReservations'	, $this->_listOfRerservations);
    	$this->page->smarty()->assign('announcementManager'	, $this->_announcementManager);
    	$this->page->smarty()->assign('profilesManager'		, $this->_profilesManager);
		$this->page->smarty()->assign('addressesManager'	, $this->_addressesManager);
		$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    }
	
	public function executeReservationExists(HTTPRequest $request)
    {
		$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    }
    
	public function executeReservationNew(HTTPRequest $request)
    {
    	if(!$request->postExists('user-subscriber-id'))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	$userId = htmlspecialchars($request->postData('user-subscriber-id'));
    	$profile = $this->_profilesManager->getByUserId($userId);
    	if(is_null($profile))
    	{
    		$this->app->httpResponse()->redirect('/profile');
    		exit();
    	}
    	
    	$reservation = new AnnouncementReservation();
    	$this->parsePostReservation($request, $reservation);
    	
    	if($this->_announcementReservationManager->isReservationExists($reservation))
    	{
    		$this->app->httpResponse()->redirect('/activities/reservation-exists');
    		exit();
    	}
    	
    	$reservation->setStateId(PaiementStates::WAITING_PAIEMENT);
    	
    	$nbDays = null;
    	if($reservation->getDateOption() == 'period')
    	{
    		$nbDays = $this->daysDifference($reservation->getDateEnd(), $reservation->getDate()) + 1;
    	}
    	
    	$currency = null;
    	$currencyId = htmlspecialchars($request->postData('currency-id'));
    	
    	if($currencyId != 'default') {
    		$currency = $this->_alternateCurrencyManager->get($currencyId);
    	}
    	
    	$announce 		= $this->_announcementManager->get($reservation->getAnnouncementId());
    	$listOfContacts	= $this->_contactsManager->getListOf($announce->getUserId());
    	$listOfPrices	= $this->_announcementPricesManager->getByAnnouncementId($announce->id());
    	
        $this->page->smarty()->assign('platform_fee_ratio', Tipkin\Config::get('platform-fee-ratio'));
    	$this->page->smarty()->assign('currency'		, $currency);
    	$this->page->smarty()->assign('reservation'		, $reservation);
    	$this->page->smarty()->assign('nbDays'			, $nbDays);
    	$this->page->smarty()->assign('announce'		, $announce);
    	$this->page->smarty()->assign('listOfContacts'	, $listOfContacts);
    	$this->page->smarty()->assign('listOfPrices'	, $listOfPrices);
    	$this->page->smarty()->assign('profilesManager'	, $this->_profilesManager);
    }
    
	public function executeReservationLandingCancel(HTTPRequest $request)
    {
    	$this->reservationRedirect($request);
    	
    	$idReservation = htmlspecialchars($this->app->httpRequest()->getData('reservationId'));
		$reservation = $this->_announcementReservationManager->get($idReservation);
		
		$reservation->setStateId(PaiementStates::CANCELED);
		$reservation->setUpdatedTime(time());
    	$reservation->setKeyCheck(null);
    	
    	$this->_announcementReservationManager->save($reservation);
    	
    	$messageMail = new Mail();
    	$messageMail->sendReservationSubscriberCanceled($this->_usersManager->get($reservation->getUserOwnerId()), 
    													$this->_usersManager->get($reservation->getUserSubscriberId()), 
    													$this->_announcementManager->get($reservation->getAnnouncementId()));
		$messageMail->sendAdminReservationSubscriberCanceled($this->_usersManager->get($reservation->getUserOwnerId()), 
														     $this->_usersManager->get($reservation->getUserSubscriberId()), 
														     $this->_announcementManager->get($reservation->getAnnouncementId()),
														     $reservation);
    }
     
	public function executeReservationLandingValid(HTTPRequest $request)
    {
    	$this->reservationRedirect($request);
    	
		$idReservation = htmlspecialchars($this->app->httpRequest()->getData('reservationId'));
		$reservation = $this->_announcementReservationManager->get($idReservation);

		$reservation->setStateId(PaiementStates::VALIDATED);
		$reservation->setUpdatedTime(time());
    	$reservation->setKeyCheck(null);
    	
    	$this->_announcementReservationManager->save($reservation);
    	
    	$messageMail = new Mail();
    	$messageMail->sendReservationSubscriberValidated($this->_usersManager->get($reservation->getUserOwnerId()), 
    													 $this->_usersManager->get($reservation->getUserSubscriberId()), 
    													 $this->_announcementManager->get($reservation->getAnnouncementId()), 
    													 $this->_profilesManager->getByUserId($reservation->getUserOwnerId()));
    													 
		$messageMail->sendReservationOwnerValidated($this->_usersManager->get($reservation->getUserOwnerId()), 
													$this->_usersManager->get($reservation->getUserSubscriberId()), 
													$this->_announcementManager->get($reservation->getAnnouncementId()), 
													$reservation);    													
    }
    
    public function executeReservationLanding(HTTPRequest $request)
    {
    	if(!$request->postExists('user-subscriber-id'))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	$reservation = new AnnouncementReservation();
    	$this->parsePostReservation($request, $reservation);
    	
    	if($this->_announcementReservationManager->isReservationExists($reservation))
    	{
    		$this->app->httpResponse()->redirect('/activities/reservation-exists');
    		exit();
    	}
    	
    	$reservation->setStateId(PaiementStates::WAITING_PAIEMENT);
    	$reservation->setKeyCheck(mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand());
    	$reservation->setTransactionRef($reservation->id());
    	
    	$this->_announcementReservationManager->save($reservation);
    	
        $platformFee = $reservation->getPrice() * Tipkin\Config::get('platform-fee-ratio');
        if($platformFee == 0 || $request->postData('currency-id') != 'default')
    	{
    		$reservation->setStateId(PaiementStates::WAITING_VALIDATION);
    		$reservation->setTransactionRef('FREE');
	    	$this->_announcementReservationManager->save($reservation);
	    	
	    	$messageMail = new Mail();
	    	$messageMail->sendReservationOwnerValidation($this->_usersManager->get($reservation->getUserOwnerId()), 
	    												 $this->_usersManager->get($reservation->getUserSubscriberId()), 
	    												 $this->_announcementManager->get($reservation->getAnnouncementId()), 
	    												 $reservation);
	    	$messageMail->sendReservationSubscriberRecap($this->_usersManager->get($reservation->getUserOwnerId()), 
	    									   			 $this->_usersManager->get($reservation->getUserSubscriberId()), 
	    									   			 $this->_announcementManager->get($reservation->getAnnouncementId()));
	    									   			 
	    	$this->app->httpResponse()->redirect('/activities/reservations');
	    	exit();
    	}
    	else 
    	{
    		$this->app->httpResponse()->redirect('/paiement/' . $reservation->id());
    		exit();
    	}
    }
    
    public function executeDeleteReservation(HTTPRequest $request)
    {
    	$reservationId = htmlspecialchars($request->getData('reservationId'));
    	
    	$reservation = $this->_announcementReservationManager->get($reservationId);
    	if(!is_null($reservation) && $reservation->getStateId() == PaiementStates::WAITING_PAIEMENT)
    	{
    		$this->_announcementReservationManager->delete($reservation->id());
    		
    		$this->app->user()->setFlash('reservation-deleted');
    	}

    	$this->app->httpResponse()->redirect('/activities/reservations');
    	exit();
    }

    //Cette fonction permet d'afficher une page de retour si un lien de validation a déja été cliqué
    public function executeReservationRebound(HTTPRequest $request)
    {
        $reservationId = htmlspecialchars($request->getData('reservationId'));
        
        $reservation = $this->_announcementReservationManager->get($reservationId);
        
        $this->page->smarty()->assign('paiementState', $reservation->getStateId());
    }
    
	private function parsePostReservation(HTTPRequest $request, AnnouncementReservation $reservation)
    {
    	$date 				= htmlspecialchars($request->postData('date'));
    	$dateEnd			= htmlspecialchars($request->postData('date-end'));
    	$dateOption			= htmlspecialchars($request->postData('date-option'));
    	$announcementId		= htmlspecialchars($request->postData('announcement-id'));
    	$userOwnerId		= htmlspecialchars($request->postData('user-owner-id'));
    	$userSubscriberId	= htmlspecialchars($request->postData('user-subscriber-id'));
    	$contactGroupId		= htmlspecialchars($request->postData('contact-group-id'));
    	$price				= htmlspecialchars($request->postData('price'));
    	$paiementStateId	= htmlspecialchars($request->postData('state-id'));
    	
    	$reservation->setAnnouncementId($announcementId);
    	$reservation->setDate($date);
    	if($dateOption == 'period')
    		$reservation->setDateEnd($dateEnd);
    	else
    		$reservation->setDateEnd($date);
    	$reservation->setDateOption($dateOption);
    	$reservation->setUserOwnerId($userOwnerId);
    	$reservation->setUserSubscriberId($userSubscriberId);
    	$reservation->setContactGroupId($contactGroupId);
    	$reservation->setPrice($price);
    	$reservation->setStateId($paiementStateId);    	
    }
    
    private function daysDifference($endDate, $beginDate)
    {
	   //explode the date by "-" and storing to array
	   $date_parts1=explode("-", $beginDate);
	   $date_parts2=explode("-", $endDate);
	   //gregoriantojd() Converts a Gregorian date to Julian Day Count
	   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
	   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
	   return $end_date - $start_date;
	}
    
    private function reservationRedirect(HTTPRequest $request)
    {
    		if($request->getExists('reservationId') && $request->getExists('keyCheck'))
			{
				$idReservation = htmlspecialchars($request->getData('reservationId'));
				$reservation = $this->_announcementReservationManager->get($idReservation);
				
				//Si notre lien de réservation a déjà été "consommé" on redirige vers une page de "Rebond"
				if(is_null($reservation->getKeyCheck())){
                    $this->app->httpResponse()->redirect('/reservations/rebound/'.$reservation->id());
                    exit();
                }
                elseif($reservation->getKeyCheck() != $request->getData('keyCheck'))
				{
					$this->app->httpResponse()->redirect404();
        			exit;
				}
			}
			else 
			{
        		$this->app->httpResponse()->redirect404();
        		exit;
			}
    }
    
	private function authenticationRedirection()
	{
		if($this->action != 'reservationLandingValid' && $this->action != 'reservationLandingCancel')
		{
    		if(!$this->app->user()->isAuthenticated())
    		{
        		$this->app->httpResponse()->redirect('/login');
        		exit;
    		}
		}
	}
    
    private function init()
    {
    	$this->_usersManager 					= $this->managers->getManagerOf('users');
    	$this->_announcementReservationManager 	= $this->managers->getManagerOf('announcementreservations');
    	$this->_announcementManager 			= $this->managers->getManagerOf('announcements');
    	$this->_profilesManager		 			= $this->managers->getManagerOf('profiles');
    	$this->_contactsManager					= $this->managers->getManagerOf('contacts');
		$this->_addressesManager				= $this->managers->getManagerOf('addresses');
    	$this->_announcementPricesManager 		= $this->managers->getManagerOf('announcementprices');
    	$this->_alternateCurrencyManager 		= $this->managers->getManagerOf('alternateCurrency');
    	
    	if($this->app->user()->isAuthenticated())
    		$this->_listOfRerservations				= $this->_announcementReservationManager->getByUserId($this->app->user()->getAttribute('id'));
    }
    
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'reservation-deleted':
				$message = 'La réservation a été supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
}

?>
