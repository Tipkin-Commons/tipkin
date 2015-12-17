<?php

class CarrouselController extends BackController
{	
	protected $_user;
	
	protected $_usersManager;
	protected $_carrouselsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->authenticationRedirection();
		
		$this->displayInfoMessage();
    }
	
	public function executeAdd(HTTPRequest $request)
	{	
		$announceId = $request->getData('announceId');
		$carrousel = new Carrousel();
		$carrousel->setAnnounceId($announceId);
		
		$this->_carrouselsManager->save($carrousel);
		
		$this->app->httpResponse()->redirect('/view/member/announce-' . $announceId);
		exit();
	}
    
	public function executeDelete(HTTPRequest $request)
	{	
		$id = $request->getData('carrouselId');
		$carrousel = $this->_carrouselsManager->get($id);
		$announceId = $carrousel->getAnnounceId();
		
		$this->_carrouselsManager->delete($id);
		
		$this->app->httpResponse()->redirect('/view/member/announce-' . $announceId);
		exit();
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
    	$this->_carrouselsManager		= $this->managers->getManagerOf('carrousels');
    	
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