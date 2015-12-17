<?php

class HistoryController extends BackController
{	
	protected $_user;
	
	protected $_usersManager;
	protected $_reservationsManager;
	protected $_announcementsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->authenticationRedirection();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{	
		$this->page->smarty()->assign('reservationsManager', $this->_reservationsManager);
		$this->page->smarty()->assign('announcementsManager', $this->_announcementsManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('platform_fee_ratio', Tipkin\Config::get('platform-fee-ratio'));
	}
	
	public function executeProceed(HTTPRequest $request)
	{	
		$reservationid = $request->getData('reservationId');
		$reservation = $this->_reservationsManager->get($reservationid);
		
		$reservation->setAdminProceed(true);
		
		$reservation->setUpdatedTime(time());
		
		$this->_reservationsManager->save($reservation);
		
		$this->app->httpResponse()->redirect('/admin/history');
		exit();
	}
	
	public function executeCancel(HTTPRequest $request)
	{	
		$reservationid = $request->getData('reservationId');
		$reservation = $this->_reservationsManager->get($reservationid);
		
		if($this->app->httpRequest()->postExists('submit-form'))
		{
			$reservation->setStateId(PaiementStates::CANCELED);
			
			$reservation->setUpdatedTime(time());
			
			$this->_reservationsManager->save($reservation);
			
			$this->app->httpResponse()->redirect('/admin/history');
			exit();
		}
		
		$this->page->smarty()->assign('reservation', $reservation);
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
    	$this->_usersManager			= $this->managers->getManagerOf('users');
    	$this->_reservationsManager		= $this->managers->getManagerOf('announcementreservations');
    	$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
    	
    	$this->_admin = $this->_usersManager->get($this->app->user()->getAttribute('admin-id'));

        if($this->_admin->getRoleId() < Role::ROLE_ADMINISTRATEUR)
        {
        	$this->app->httpResponse()->redirect('/admin/../');
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
				case 'opinion-deleted':
				$message = 'Témoignage supprimé !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'opinion-published':
				$message = 'Témoignage publié !';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
}

?>
