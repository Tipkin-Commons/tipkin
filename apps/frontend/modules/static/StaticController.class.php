<?php

class StaticController extends BackController 
{
	protected $_categoriesManager;
	protected $_regionsManager;
	protected $_departmentsManager;
	protected $_userManager;
	protected $_announcementsManager;
	protected $_profilesManager;
	protected $_contactsManager;
	protected $_opinionsManager;
	protected $_carrouselsManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
    }
    
	public function executeIndex(HTTPRequest $request)
	{
		$carrouselsList = $this->_carrouselsManager->getListOf();
		
		$listOfAnnouncements = array();
		foreach ($carrouselsList as $carrousel)
		{
			$listOfAnnouncements[] = $this->_announcementsManager->get($carrousel->getAnnounceId()); 
		}
		
		//$listOfAnnouncements = $this->_announcementsManager->getListOfLastPublished(20);
		
		$categories 	= $this->_categoriesManager->getListOf();
		$regions 		= $this->_regionsManager->getListOf();
		$departments 	= $this->_departmentsManager->getListOf();
		
		$this->page->smarty()->assign('categories'			, $categories);
		$this->page->smarty()->assign('regions'				, $regions);
		$this->page->smarty()->assign('departments'			, $departments);
		
		$this->page->smarty()->assign('profilesManager'		, $this->_profilesManager);
		$this->page->smarty()->assign('userManager'			, $this->_userManager);
		$this->page->smarty()->assign('contactsManager'		, $this->_contactsManager);
		$this->page->smarty()->assign('carrouselsManager'	, $this->_carrouselsManager);
		$this->page->smarty()->assign('listOfAnnouncements'	, $listOfAnnouncements);
	}
	
	public function executeFaq(HTTPRequest $request)
	{
		if(Tipkin\Config::get('platform-fee-ratio') > 0) {
			$this->page->smarty()->assign('template','faq_with_platform_fee');
		} else {
			$this->page->smarty()->assign('template','faq_without_platform_fee');
		}
	}
	
	public function executeLegals(HTTPRequest $request)
	{
		
	}
	
	public function executeHowItWork(HTTPRequest $request)
	{
		
	}
	
	public function executeOpinion(HTTPRequest $request)
	{
		if($request->postExists('submit-form'))
		{
			$opinion = new WebsiteOpinion();
			$opinion->setUsername(htmlspecialchars($request->postData('username')));
			$opinion->setComment(htmlspecialchars($request->postData('comment')));
			
			$this->_opinionsManager->save($opinion);
			$this->page->smarty()->assign('isMessageSent', true);
		}
		
		$this->page->smarty()->assign('opinionsManager'		, $this->_opinionsManager);
	}
	
	public function executeContact(HTTPRequest $request)
	{
		if($request->postExists('email'))
		{
			if($this->isContactMessageValid($request))
			{
				$subject 	= htmlspecialchars($request->postData('subject'));
				$email 		= htmlspecialchars($request->postData('email'));

				$message	= htmlspecialchars($request->postData('message'));
				
				
				$messageMail = new Mail();
				$messageMail->to 		= 'contact@tipkin.fr,postmaster@beta.tipkin.fr';
				$messageMail->from 		= $email;
				$messageMail->subject	= date('d-m-y h:i:s').'[CONTACTEZ-NOUS] ' . $subject;
				$messageMail->content 	= $message;

				$messageMail->send();
				
				if($request->postExists('send-copy'))
				{
					$messageMail->to 		= $email;
					$messageMail->from 		= null;
					$messageMail->subject	= '[TIPKIN] Copie de votre message : ' . $subject;
	
					$messageMail->send();
				}
				
				$this->page->smarty()->assign('isMessageSent', true);
			}
		}
	}
	
	private function isContactMessageValid(HTTPRequest $request)
	{
		$subject 	= htmlspecialchars($request->postData('subject'));
		$email 		= htmlspecialchars($request->postData('email'));

		$message	= htmlspecialchars($request->postData('message'));
		if(empty($email) || empty($subject) || empty($message))
			return false;
		else 
			return true;
	}

	private function init()
	{
		$this->_carrouselsManager 		= $this->managers->getManagerOf('carrousels');
		$this->_categoriesManager 		= $this->managers->getManagerOf('categories');
		$this->_regionsManager 			= $this->managers->getManagerOf('regions');
		$this->_departmentsManager 		= $this->managers->getManagerOf('departments');
		$this->_userManager		 		= $this->managers->getManagerOf('users');
		$this->_announcementsManager	= $this->managers->getManagerOf('announcements');
		$this->_profilesManager			= $this->managers->getManagerOf('profiles');
		$this->_contactsManager			= $this->managers->getManagerOf('contacts');
		$this->_opinionsManager			= $this->managers->getManagerOf('websiteopinions');
	}
}

?>
