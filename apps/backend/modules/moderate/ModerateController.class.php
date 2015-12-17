<?php

class ModerateController extends BackController
{	
	protected $_user;
	
	protected $_usersManager;
	protected $_announcementsManager;
	protected $_feedbacksManager;
	protected $_profilesManager;
	protected $_moderatesManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->authenticationRedirection();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{	
		$this->page->smarty()->assign('moderatesManager', $this->_moderatesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('announcementsManager', $this->_announcementsManager);
		$this->page->smarty()->assign('feedbacksManager', $this->_feedbacksManager);
	}
	
	public function executeDeleteModerateAnnounce(HTTPRequest $request)
	{	
		$moderateId = $request->getData('moderateId');
		
		$moderate = $this->_moderatesManager->get($moderateId);
		
		if($request->postExists('submit-form'))
		{
			$this->_moderatesManager->delete($moderateId);
			$this->app->user()->setFlash('moderate-deleted');
			
			$this->app->httpResponse()->redirect('/admin/moderate');
			exit();
		}
		
		$this->page->smarty()->assign('moderate', $moderate);
		$this->page->smarty()->assign('moderatesManager', $this->_moderatesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('announcementsManager', $this->_announcementsManager);
	}
	
	public function executeDeleteModerateFeedback(HTTPRequest $request)
	{	
		$moderateId = $request->getData('moderateId');
		
		$moderate = $this->_moderatesManager->get($moderateId);
		
		if($request->postExists('submit-form'))
		{
			$this->_moderatesManager->delete($moderateId);
			$this->app->user()->setFlash('moderate-deleted');
			
			$this->app->httpResponse()->redirect('/admin/moderate');
			exit();
		}
		
		$this->page->smarty()->assign('moderate', $moderate);
		$this->page->smarty()->assign('moderatesManager', $this->_moderatesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('feedbacksManager', $this->_feedbacksManager);
	}
	
	public function executeDeleteFeedback(HTTPRequest $request)
	{
		$feedbackId 	= htmlspecialchars($request->getData('feedbackId'));
		$feedback = $this->_feedbacksManager->get($feedbackId);
		
		if($request->postExists('submit-form'))
		{
			$this->_moderatesManager->deleteByFeedbackId($feedbackId);
			$this->_feedbacksManager->delete($feedbackId);
			
			$this->app->user()->setFlash('feedback-deleted');
			
			$this->app->httpResponse()->redirect('/admin/moderate');
			exit();
		}
		
		$this->page->smarty()->assign('feedback', $feedback);
		$this->page->smarty()->assign('feedbacksManager', $this->_feedbacksManager);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
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
    	$this->_profilesManager			= $this->managers->getManagerOf('profiles');
    	$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
    	$this->_feedbacksManager		= $this->managers->getManagerOf('feedbacks');
    	$this->_moderatesManager		= $this->managers->getManagerOf('moderates');
    	
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
				case 'moderate-deleted':
				$message = 'Modération supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'feedback-deleted':
				$message = 'Feedback supprimé !';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
}

?>