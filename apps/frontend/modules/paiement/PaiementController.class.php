<?php

class PaiementController extends BackController
{
	protected $_usersManager;
	protected $_announcementManager;
	protected $_announcementReservationManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->displayInfoMessage();
    } 
    
	public function executeIndex(HTTPRequest $request)
    {
    	if(!$this->app->user()->isAuthenticated())
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	if(Config::get('platform-fee-ratio') == 0)
      {
        $this->app->httpResponse()->redirect404();
        exit();
      }

    	$reservationId = htmlspecialchars($request->getData('reservationId'));
    	$reservation = $this->_announcementReservationManager->get($reservationId);
    	
    	if(is_null($reservation))
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	$userId = $this->app->user()->getAttribute('id');
    	
    	if($userId != $reservation->getUserSubscriberId() || $reservation->getStateId() != PaiementStates::WAITING_PAIEMENT)
    	{
    		$this->app->httpResponse()->redirect404();
    		exit();
    	}
    	
    	$this->page->smarty()->assign('announcementReservationManager'	, $this->_announcementReservationManager);
    	$this->page->smarty()->assign('usersManager'	, $this->_usersManager);
    	$this->page->smarty()->assign('reservation'		, $reservation);
      $this->page->smarty()->assign('amount', round($reservation->getPrice() * Tipkin\Config::get('platform-fee-ratio'), 2));
    }
    
	public function executeBack(HTTPRequest $request)
    {
    	error_reporting(E_ALL ^ E_NOTICE);
    	
    	header("Pragma: no-cache");
		header("Content-type: text/plain");
		
		// TPE Settings
		// Warning !! CMCIC_Config contains the key, you have to protect this file with all the mechanism available in your development environment.
		// You may for instance put this file in another directory and/or change its name. If so, don't forget to adapt the include path below.
		require_once(dirname(__FILE__).'/../../../../lib/paiement/CMCIC_Config.php');
		
		// --- PHP implementation of RFC2104 hmac sha1 ---
		require_once(dirname(__FILE__).'/../../../../lib/paiement/CMCIC_Tpe.inc.php');
		
		
		// Begin Main : Retrieve Variables posted by CMCIC Payment Server 
		$CMCIC_bruteVars = getMethode();
		
		// TPE init variables
		$oTpe = new CMCIC_Tpe();
		$oHmac = new CMCIC_Hmac($oTpe);
		
		// Message Authentication
		$cgi2_fields = sprintf(CMCIC_CGI2_FIELDS, $oTpe->sNumero,
							  $CMCIC_bruteVars["date"],
						          $CMCIC_bruteVars['montant'],
						          $CMCIC_bruteVars['reference'],
						          $CMCIC_bruteVars['texte-libre'],
						          $oTpe->sVersion,
						          $CMCIC_bruteVars['code-retour'],
							  $CMCIC_bruteVars['cvx'],
							  $CMCIC_bruteVars['vld'],
							  $CMCIC_bruteVars['brand'],
							  $CMCIC_bruteVars['status3ds'],
							  $CMCIC_bruteVars['numauto'],
							  $CMCIC_bruteVars['motifrefus'],
							  $CMCIC_bruteVars['originecb'],
							  $CMCIC_bruteVars['bincb'],
							  $CMCIC_bruteVars['hpancb'],
							  $CMCIC_bruteVars['ipclient'],
							  $CMCIC_bruteVars['originetr'],
							  $CMCIC_bruteVars['veres'],
							  $CMCIC_bruteVars['pares']
							);
		
		
		if ($oHmac->computeHmac($cgi2_fields) == strtolower($CMCIC_bruteVars['MAC']))
			{
			switch($CMCIC_bruteVars['code-retour']) {
				case "Annulation" :
					// Payment has been refused
					// put your code here (email sending / Database update)
					// Attention : an autorization may still be delivered for this payment
					break;
		
				case "payetest":
					// Payment has been accepeted on the test server
					// put your code here (email sending / Database update)
					$reservationId = $request->postData('reference');
		    		$reservation = $this->_announcementReservationManager->get($reservationId);
		    		if(!is_null($reservation))
		    		{
				    	$reservation->setStateId(PaiementStates::WAITING_VALIDATION);
				    	$reservation->setTransactionRef($reservation->id());
				    	$this->_announcementReservationManager->save($reservation);
				    	
				    	$messageMail = new Mail();
				    	$messageMail->sendReservationOwnerValidation($this->_usersManager->get($reservation->getUserOwnerId()), 
				    												 $this->_usersManager->get($reservation->getUserSubscriberId()), 
				    												 $this->_announcementManager->get($reservation->getAnnouncementId()), 
				    												 $reservation);
				    	$messageMail->sendReservationSubscriberRecap($this->_usersManager->get($reservation->getUserOwnerId()), 
				    									   			 $this->_usersManager->get($reservation->getUserSubscriberId()), 
				    									   			 $this->_announcementManager->get($reservation->getAnnouncementId()));
			    	}
					break;
		
				case "paiement":
					// Payment has been accepted on the productive server
					// put your code here (email sending / Database update)
					$reservationId = $request->postData('reference');
		    		$reservation = $this->_announcementReservationManager->get($reservationId);
		    		if(!is_null($reservation))
		    		{
				    	$reservation->setStateId(PaiementStates::WAITING_VALIDATION);
				    	$reservation->setTransactionRef($reservation->id());
				    	$this->_announcementReservationManager->save($reservation);
				    	
				    	$messageMail = new Mail();
				    	$messageMail->sendReservationOwnerValidation($this->_usersManager->get($reservation->getUserOwnerId()), 
				    												 $this->_usersManager->get($reservation->getUserSubscriberId()), 
				    												 $this->_announcementManager->get($reservation->getAnnouncementId()), 
				    												 $reservation);
				    	$messageMail->sendReservationSubscriberRecap($this->_usersManager->get($reservation->getUserOwnerId()), 
				    									   			 $this->_usersManager->get($reservation->getUserSubscriberId()), 
				    									   			 $this->_announcementManager->get($reservation->getAnnouncementId()));
			    	}
					break;
		
		
				/*** ONLY FOR MULTIPART PAYMENT ***/
				case "paiement_pf2":
				case "paiement_pf3":
				case "paiement_pf4":
					// Payment has been accepted on the productive server for the part #N
					// return code is like paiement_pf[#N]
					// put your code here (email sending / Database update)
					// You have the amount of the payment part in $CMCIC_bruteVars['montantech']
					break;
		
				case "Annulation_pf2":
				case "Annulation_pf3":
				case "Annulation_pf4":
					// Payment has been refused on the productive server for the part #N
					// return code is like Annulation_pf[#N]
					// put your code here (email sending / Database update)
					// You have the amount of the payment part in $CMCIC_bruteVars['montantech']
					break;
					
			}
		
			$receipt = CMCIC_CGI2_MACOK;
		
		}
		else
		{
			// your code if the HMAC doesn't match
			$receipt = CMCIC_CGI2_MACNOTOK.$cgi2_fields;
		}
		
		//-----------------------------------------------------------------------------
		// Send receipt to CMCIC server
		//-----------------------------------------------------------------------------
		printf (CMCIC_CGI2_RECEIPT, $receipt);
		
		// Copyright (c) 2009 Euro-Information ( mailto:centrecom@e-i.com )
		// All rights reserved. --- 
    }
    
	public function executeOk(HTTPRequest $request)
    {
    	$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    }
    
	public function executeError(HTTPRequest $request)
    {
    	$this->page->smarty()->assign('usersManager'		, $this->_usersManager);
    }
    
    public function executeTest(HTTPRequest $request)
    {
    	require_once (dirname(__FILE__).'/../../../../lib/paiement/Phase1Aller.php');
    }
    
    private function init()
    {
    	$this->_usersManager 					= $this->managers->getManagerOf('users');
    	$this->_announcementManager 			= $this->managers->getManagerOf('announcements');
    	$this->_announcementReservationManager 	= $this->managers->getManagerOf('announcementreservations');
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
				
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
}

?>
