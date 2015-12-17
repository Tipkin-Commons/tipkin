<?php

class FeedbackController extends BackController
{	
	protected $_user;
	
	protected $_usersManager;
	protected $_profilesManager;
	protected $_feedbacksManager;
	protected $_feedbackRequestsManager;
	protected $_announcementsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
		
		$this->displayInfoMessage();
    }
    
	public function executeIndex(HTTPRequest $request)
	{
		$this->authenticationRedirection();
		$listOfFeedbackRequests = $this->_feedbackRequestsManager->getByUserId($this->_user->id());
		
		$this->page->smarty()->assign('listOfFeedbackRequests', $listOfFeedbackRequests);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('announcementsManager', $this->_announcementsManager);
	}
	
	public function executeLeave(HTTPRequest $request)
	{	
		$this->authenticationRedirection();
		if(!$request->getExists('feedbackRequestId'))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		$feedbackRequestId 	= htmlspecialchars($request->getData('feedbackRequestId'));
		$feedbackRequest 	= $this->_feedbackRequestsManager->get($feedbackRequestId);
		
		if(is_null($feedbackRequest))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('submit-form'))
		{
			$feedback = new Feedback();
			
			$feedback->setAnnounceId($feedbackRequest->getAnnounceId());
			$feedback->setUserAuthorId($feedbackRequest->getUserAuthorId());
			$feedback->setUserOwnerId($feedbackRequest->getUserOwnerId());
			$feedback->setUserSubscriberId($feedbackRequest->getUserSubscriberId());
			$feedback->setReservationId($feedbackRequest->getReservationId());
			
			$mark 		= htmlspecialchars($request->postData('mark'));
			$comment 	= htmlspecialchars($request->postData('comment'));
			
			$feedback->setMark($mark);
			$feedback->setComment($comment);
			
			$this->_feedbacksManager->save($feedback);
			$this->_feedbackRequestsManager->delete($feedbackRequest->id());
			
			$this->app->user()->setFlash('feedback-saved');
			
			$this->app->httpResponse()->redirect('/feedback');
			exit();
		}
		
		$this->page->smarty()->assign('feedbackRequest', $feedbackRequest);
	}
	
	public function executeUserList(HTTPRequest $request)
	{
		$userId 	= htmlspecialchars($request->getData('userId'));
		$listOfFeedbacks = $this->_feedbacksManager->getByUserId($userId);
		
		$this->page->smarty()->assign('listOfFeedbacks', $listOfFeedbacks);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
	}
	
	public function executeFeedback(HTTPRequest $request)
	{
		$feedbackId 	= htmlspecialchars($request->getData('feedbackId'));
		$feedback = $this->_feedbacksManager->get($feedbackId);
		
		$this->page->smarty()->assign('feedback', $feedback);
		$this->page->smarty()->assign('feedbacksManager', $this->_feedbacksManager);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
	}
	
	public function executeAnnounceList(HTTPRequest $request)
	{
		$announceId 	= htmlspecialchars($request->getData('announceId'));
		$listOfFeedbacks = $this->_feedbacksManager->getByAnnounceId($announceId);
		
		$this->page->smarty()->assign('listOfFeedbacks', $listOfFeedbacks);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
	}
	
	private function authenticationRedirection()
	{
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
        	
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
    
    private function init()
    {
    	$this->_usersManager			= $this->managers->getManagerOf('users');
    	$this->_profilesManager			= $this->managers->getManagerOf('profiles');
    	$this->_feedbacksManager		= $this->managers->getManagerOf('feedbacks');
    	$this->_feedbackRequestsManager	= $this->managers->getManagerOf('feedbackrequests');
    	$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
    }
    
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'feedback-saved':
				$message = 'Votre feedback a été enregistré !';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
	
	
}

?>