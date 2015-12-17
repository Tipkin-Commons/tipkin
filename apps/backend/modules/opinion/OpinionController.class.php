<?php

class OpinionController extends BackController
{	
	protected $_user;
	
	protected $_usersManager;
	protected $_opinionsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->authenticationRedirection();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{	
		$this->page->smarty()->assign('opinionsManager', $this->_opinionsManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
	}
	
	public function executeDelete(HTTPRequest $request)
	{	
		$opinionId = $request->getData('opinionId');
		
		$opinion = $this->_opinionsManager->get($opinionId);
		
		if($request->postExists('submit-form'))
		{
			$this->_opinionsManager->delete($opinionId);
			$this->app->user()->setFlash('opinion-deleted');
			
			$this->app->httpResponse()->redirect('/admin/opinion');
			exit();
		}
		
		$this->page->smarty()->assign('opinion', $opinion);
		$this->page->smarty()->assign('opinionsManager', $this->_opinionsManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
	}
	
	public function executePublish(HTTPRequest $request)
	{	
		$opinionId = $request->getData('opinionId');
		
		$opinion = $this->_opinionsManager->get($opinionId);
		
		if($request->postExists('submit-form'))
		{
			
			$opinion->setIsPublished(true);
			$this->_opinionsManager->save($opinion); 
			
			$this->app->user()->setFlash('opinion-published');
			
			$this->app->httpResponse()->redirect('/admin/opinion');
			exit();
		}
		
		$this->page->smarty()->assign('opinion', $opinion);
		$this->page->smarty()->assign('opinionsManager', $this->_opinionsManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
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
    	$this->_opinionsManager			= $this->managers->getManagerOf('websiteopinions');
    	
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