<?php
    abstract class BackController extends ApplicationComponent
    {
        protected $action = '';
        protected $managers = null;
        protected $module = '';
        protected $page = null;
        protected $view = '';
        
        public function __construct(Application $app, $module, $action)
        {
            parent::__construct($app);
            
            $this->managers = new Managers('pdo', PDOFactory::getMysqlConnexion());
            $this->page = new Page($app);
            
            $this->setModule($module);
            $this->setAction($action);
            $this->setView($action);
            
            if($this->app->name() == 'frontend')
            	$this->initUser();
            else
            	$this->initAdmin();
        }
        
        public function execute()
        {
            $method = 'execute'.ucfirst($this->action);
            
            if (!is_callable(array($this, $method)))
            {
                throw new RuntimeException('L\'action "'.$this->action.'" n\'est pas définie sur ce module');
            }
            
            $this->$method($this->app->httpRequest());
            
            $this->page->smarty()->assign('isAuthenticate', 'false');
            if($this->app()->user()->isAuthenticated())
            	$this->page->smarty()->assign('isAuthenticate', 'true');
            
            $this->page->smarty()->assign('isAdminAuthenticate', 'false');
            if($this->app()->user()->isAdminAuthenticated())
            	$this->page->smarty()->assign('isAdminAuthenticate', 'true');
            
            $this->page->smarty()->display($this->view);
        }
        
        public function page()
        {
            return $this->page;
        }
        
        public function setModule($module)
        {
            if (!is_string($module) || empty($module))
            {
                throw new InvalidArgumentException('Le module doit être une chaine de caractères valide');
            }
            
            $this->module = $module;
        }
        
        public function setAction($action)
        {
            if (!is_string($action) || empty($action))
            {
                throw new InvalidArgumentException('L\'action doit être une chaine de caractères valide');
            }
            
            $this->action = $action;
        }
        
        public function setView($view)
        {
            if (!is_string($view) || empty($view))
            {
                throw new InvalidArgumentException('La vue doit être une chaine de caractères valide');
            }
            
            $this->view = $view . '.tpl';
            
            $this->page->addTemplateDir(dirname(__FILE__). '/../apps/' . $this->app->name() . '/templates/');
            
            $this->page->addTemplateDir(dirname(__FILE__).'/../apps/'.$this->app->name().'/modules/'.$this->module.'/views/');
            
        }
        
        private function initAdmin()
        {
        	$announcesManager = $this->managers->getManagerOf('announcements');
        	$moderatesManager = $this->managers->getManagerOf('moderates');
        	$opinionsManager = $this->managers->getManagerOf('websiteopinions');
        	
        	$nbAnnouncePendings = count($announcesManager->getPendings());
        	$nbModerates = count($moderatesManager->getListOf(Moderate::TYPE_ANNOUNCEMENT)) + count($moderatesManager->getListOf(Moderate::TYPE_FEEDBACK));
        	$nbOpinions = 0;
        	foreach ($opinionsManager->getListOf() as $opinion)
        	{
        		if(!$opinion->getIsPublished())
        			$nbOpinions++;
        	}

        	$this->page->smarty()->assign('nbAnnouncePendings', $nbAnnouncePendings);
        	$this->page->smarty()->assign('nbModerates', $nbModerates);
        	$this->page->smarty()->assign('nbOpinions', $nbOpinions);
        	
        }
        
        private function initUser()
        {
        	if(!$this->app->user()->isAuthenticated() && $this->app->httpRequest()->cookieExists('tipkin-id'))
        	{
        		$this->app->user()->setAttribute('id', $this->app->httpRequest()->cookieData('tipkin-id')) ;
        		$this->app->user()->setAuthenticated(true);
        	}
        	
        	$userId = $this->app->user()->getAttribute('id');
		
        	$userManager = $this->managers->getManagerOf('users');
			//Initialisation de variables
			$user = $userManager->get($userId);
			
			if(is_null($user) || !$user->getIsActive())
			{
				$this->app->user()->setAuthenticated(false);
			}
			else 
			{
				$this->app->user()->setAuthenticated(true);
				
				$this->page->smarty()->assign('currentUser', $user);
				
				if($user->getRoleId() != Role::ROLE_MEMBER_PRO)
					$this->assignMenuRightVars($user);
				else 
					$this->assignMenuRightVarsPro($user);
			}
		}
		
	    private function updateAnnouncementState(Announcement $announce)
		{
			$endPublicationDate = $announce->getEndPublicationDate();
			
			$now = new DateTime();
			$endDate = new DateTime($endPublicationDate);
			
			//if($endDate->getTimestamp() < $now->getTimestamp() && $announce->getStateId() == AnnouncementStates::STATE_VALIDATED)
			//	$announce->setStateId(AnnouncementStates::STATE_ARCHIVED);
		}
		
		private function assignMenuRightVars(Users $user)
		{
			if($this->app->user()->isAuthenticated())
			{
				//Initialisation du menu de droite pour les annonces des membres
				$drafts 	= 0;
				$pending 	= 0;
				$validated 	= 0;
				$refused 	= 0;
				$archived 	= 0;
				
				$announceManager = $this->managers->getManagerOf('announcements');
				$announces = $announceManager->getListOf($user->id());
				
				foreach ($announces as $announce) 
				{
					$this->updateAnnouncementState($announce);
					$announceManager->save($announce);
					
					if($announce->getStateId() == AnnouncementStates::STATE_DRAFT)
						$drafts++;
					if($announce->getStateId() == AnnouncementStates::STATE_PENDING)
						$pending++;
					if($announce->getStateId() == AnnouncementStates::STATE_VALIDATED && $announce->getRefAnnouncementId() == null)
						$validated++;
					if($announce->getStateId() == AnnouncementStates::STATE_REFUSED)
						$refused++;
					if($announce->getStateId() == AnnouncementStates::STATE_ARCHIVED)
						$archived++;
				}
				
				$this->page->smarty()->assign('nbDrafts', $drafts);
				$this->page->smarty()->assign('nbPending', $pending);
				$this->page->smarty()->assign('nbValidated', $validated);
				$this->page->smarty()->assign('nbRefused', $refused);
				$this->page->smarty()->assign('nbArchived', $archived);
				
				//Initialisation du menu de droite pour la listes des contacts des membres
				$tippeurs 	= 0;
				$friends 	= 0;
				$family 	= 0;
				$neighbors 	= 0;
				
				$contactsManager = $this->managers->getManagerOf('contacts');
				$contacts = $contactsManager->getListOf($user->id());
				foreach ($contacts as $contact) 
				{
					if($contact->getContactGroupId() == ContactGroups::TIPPEURS)
						$tippeurs++;
					if($contact->getContactGroupId() == ContactGroups::FRIENDS)
						$friends++;
					if($contact->getContactGroupId() == ContactGroups::FAMILY)
						$family++;
					if($contact->getContactGroupId() == ContactGroups::NEIGHBORS)
						$neighbors++;
				}
				
				$this->page->smarty()->assign('nbTippeurs', $tippeurs);
				$this->page->smarty()->assign('nbFriends', $friends);
				$this->page->smarty()->assign('nbFamily', $family);
				$this->page->smarty()->assign('nbNeighbors', $neighbors);
				
				$wait = 0;
				$contactRequestsManager = $this->managers->getManagerOf('contactrequests');
				$contactRequests = $contactRequestsManager->getListOfTo($user->id());
				
				$wait = count($contactRequests);
				
				$this->page->smarty()->assign('nbWait', $wait);
				
				//Intialisation du nombre de réservations et de location
				$locations 		= 0;
				$reservations 	= 0;
				
				$announcementReservationsManager = $this->managers->getManagerOf('announcementreservations');
				$activitiesList = $announcementReservationsManager->getByUserId($user->id());
				
				$today = new DateTime();
				foreach ($activitiesList as $activities)
				{
					if($activities->getStateId() ==  PaiementStates::WAITING_VALIDATION)
					{
						if($activities->getDateOption() != 'period')
							$actitiviesDate = new DateTime($activities->getDate());
						else
							$actitiviesDate = new DateTime($activities->getDateEnd());
							
						if($activities->getUserOwnerId() == $user->id() && $actitiviesDate->getTimestamp() > $today->getTimestamp())
							$locations++;
						if($activities->getUserSubscriberId() == $user->id() && $actitiviesDate->getTimestamp() > $today->getTimestamp())
							$reservations++;
					}
				}
				
				$this->page->smarty()->assign('nbReservations', $reservations);
				$this->page->smarty()->assign('nbLocations', $locations);
				
				//Initialisation du nombre de feedback à afficher
				$feedbackRequestsManager = $this->managers->getManagerOf('feedbackrequests');
				$listOfFeedbacks = $feedbackRequestsManager->getByAuthorId($user->id());
				
				$this->page->smarty()->assign('nbFeedbackRequests', count($listOfFeedbacks));
			}
		}
		
	    private function assignMenuRightVarsPro(Users $user)
		{
			if($this->app->user()->isAuthenticated())
			{
				$drafts = 0;
				$validated = 0;
				$archived = 0;
				
				$announceManager = $this->managers->getManagerOf('announcementspro');
				$announces = $announceManager->getListOf($user->id());
				
				foreach ($announces as $announce) 
				{
					if($announce->getStateId() == AnnouncementStates::STATE_DRAFT)
						$drafts++;
					if($announce->getStateId() == AnnouncementStates::STATE_VALIDATED)
						$validated++;
					if($announce->getStateId() == AnnouncementStates::STATE_ARCHIVED)
						$archived++;
				}
				
				$this->page->smarty()->assign('nbDrafts', $drafts);
				$this->page->smarty()->assign('nbValidated', $validated);
				$this->page->smarty()->assign('nbArchived', $archived);
			}
		}
	}
?>