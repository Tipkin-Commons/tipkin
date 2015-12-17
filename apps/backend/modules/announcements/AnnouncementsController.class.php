<?php

class AnnouncementsController extends BackController 
{
	protected $_userManager;
	protected $_announcementsManager;
	protected $_categoriesManager;
	protected $_announcementPricesManager;
	protected $_announcementUnavailabilitiesManager;
	protected $_announcementReservationsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
    
    public function executeIndex(HTTPRequest $request)
    {
    	$announces = $this->_announcementsManager->getListOf();
    	$this->page->smarty()->assign('announces', $announces);
    	
    	$this->page->smarty()->assign('usersManager', $this->_userManager);
    	$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
    }
    
	public function executeValidate(HTTPRequest $request)
    {
    	$announce = $this->_announcementsManager->get($request->getData('announceId'));
    	
    	$this->page->smarty()->assign('announce', $announce);
    	
    	if($request->postExists('confirm'))
    	{
    		$currentDate = new DateTime();
			$announce->setPublicationDate($currentDate->format('Y-m-d'));
    		
    		$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
    		$announce->setAdminComment(null);
    		
    		$this->_announcementsManager->save($announce);
    		
    		$this->app->user()->setFlash('announce-validated');
    		
    		//TODO : Envoyer un mail à l'utilistateur
    		
    		$this->app->httpResponse()->redirect('/admin/announcements');
    		exit();
    	}
    }
    
	public function executeRefuse(HTTPRequest $request)
    {
    	$announce = $this->_announcementsManager->get($request->getData('announceId'));
    	
    	$this->page->smarty()->assign('announce', $announce);
    	
    	if($request->postExists('confirm'))
    	{
    		$announce->setStateId(AnnouncementStates::STATE_REFUSED);
    		$announce->setAdminComment(htmlspecialchars($request->postData('admin-comment')));
    		$this->_announcementsManager->save($announce);
    		
    		$this->app->user()->setFlash('announce-refused');
    		
    		//TODO : Envoyer un mail à l'utilistateur
    		
    		$this->app->httpResponse()->redirect('/admin/announcements');
    		exit();
    	}
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
        $this->_userManager 							= $this->managers->getManagerOf('users');
        $this->_announcementsManager 					= $this->managers->getManagerOf('announcements');
        $this->_categoriesManager	 					= $this->managers->getManagerOf('categories');
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
				
				case 'announce-validated':
				$message = 'L\'annonce vient d\'être validée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-validated':
				$message = 'L\'annonce vient d\'être refusée !';
	        	$message = MessageBox::Success($message);
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