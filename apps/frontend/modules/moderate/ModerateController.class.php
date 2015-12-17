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
	
	public function executeAnnounce(HTTPRequest $request)
	{	
		$announce = $this->_announcementsManager->get(htmlspecialchars($request->getData('announceId')));
		
		if($request->postExists('submit-form'))
		{
			
			$moderate = new Moderate();
			$moderate->setType(Moderate::TYPE_ANNOUNCEMENT);
			$moderate->setTypeId(htmlspecialchars($request->postData('announce-id')));
			$moderate->setUserAuthorId(htmlspecialchars($request->postData('user-id')));
			$moderate->setMessage(htmlspecialchars($request->postData('message')));
			
			$this->_moderatesManager->save($moderate);
			//Envoyer un mail ici
			$messageMail = new Mail();
			$messageMail->sendModerationRequest();
			
			
			$this->page->smarty()->assign('messageSent', true);
			$this->app->user()->setFlash('message-sent');
			$this->displayInfoMessage();	
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
	}
	
	public function executeFeedback(HTTPRequest $request)
	{	
		$feedback = $this->_feedbacksManager->get(htmlspecialchars($request->getData('feedbackId')));
		
		if($request->postExists('submit-form'))
		{
			
			$moderate = new Moderate();
			$moderate->setType(Moderate::TYPE_FEEDBACK);
			$moderate->setTypeId(htmlspecialchars($request->postData('feedback-id')));
			$moderate->setUserAuthorId(htmlspecialchars($request->postData('user-id')));
			$moderate->setMessage(htmlspecialchars($request->postData('message')));
			
			$this->_moderatesManager->save($moderate);
			//Envoyer un mail ici
			$messageMail = new Mail();
			$messageMail->sendModerationRequest();
			
			$this->page->smarty()->assign('messageSent', true);
			$this->app->user()->setFlash('message-sent');
			$this->displayInfoMessage();	
		}
		
		$this->page->smarty()->assign('feedback', $feedback);
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
    	$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
    	$this->_feedbacksManager		= $this->managers->getManagerOf('feedbacks');
    	$this->_moderatesManager		= $this->managers->getManagerOf('moderates');
    }
    
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'message-sent':
				$message = 'Votre demande de modération a bien été envoyé !
							<br />
							Toute l\'équipe TIPKIN vous remercie pour votre implication.';
	        	$message = MessageBox::Success($message);
				break;
			}
		}
		$this->page->smarty()->assign('message', $message);
	}
	
	
}

?>