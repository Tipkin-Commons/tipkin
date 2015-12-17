<?php
    use Tipkin\Config;
class ConnexionController extends BackController
    {
    	protected $_user;
    	/**
    	 *
    	 * @var UsersManager_PDO
    	 */
    	protected $_userManager;
    	
    	public function __construct(Application $app,$module, $action)
    	{
			parent::__construct($app, $module, $action);
    	}
    	
        public function executeIndex(HTTPRequest $request)
        {
            $this->init();
        	
        	if ($request->postExists('connect'))
            {
                $login = htmlspecialchars($request->postData('login'));
                $password = htmlspecialchars($request->postData('password'));
                $createCookie = $request->postExists('create-cookie');
                
             	$this->_user = $this->_userManager->authenticate($login, $password);
             	
                if (!is_null($this->_user))
                {
                	if($this->_user->getRoleId() >= Role::ROLE_MEMBER && $this->_user->getIsActive())
                	{
	                    $this->app->user()->setAuthenticated(true);
	                    $this->app->user()->setAttribute('id', $this->_user->id());
	                    
	                    if($createCookie)
	                    {
	                    	//On crée un cookie expirant dans un mois
	                    	$this->app->httpResponse()->setCookie('tipkin-id',$this->_user->id(), time()+60*60*24*30);
	                    }
		                    
	                    $this->authenticationRedirection();
                	}
                	else
                	{
                		$message = MessageBox::Error('Votre profil n\'est pas actif. Pensez à valider le lien qui vous a été envoyé par mail, n\'oubliez pas de vérifier vos courriers indésirables ou spams.
                										<br /><br />
                										En cas de besoin n\'hésitez pas à contacter l\'administrateur.');
                    
                		$this->page->smarty()->assign('connexionMessage', $message);
                	}
                }
                else
                {
                	$message = MessageBox::Error('Le pseudo ou le mot de passe est incorrect');
                    
                	$this->page->smarty()->assign('connexionMessage', $message);
                }
            }
        }
        
        public function executeRegistration(HTTPRequest $request)
        {
        	$this->init();
        	
        	if ($request->postExists('connect'))
        		$this->executeIndex($request);
        	
        	$message = '';
			$this->_user = new Users();
        	
        	if ($request->postExists('register'))
        	{
        		$username 				= htmlspecialchars($request->postData('username'));
        		$mail 					= htmlspecialchars($request->postData('mail'));
        		$mailConfirmation 		= htmlspecialchars($request->postData('mail-confirmation'));
        		$password 				= htmlspecialchars($request->postData('password'));
        		$passwordConfirmation	= htmlspecialchars($request->postData('password-confirmation'));
        		$mailingState				= $request->postData('mailingState');
        		if (!$mailingState) $mailingState=0;
                        
        		$role					= htmlspecialchars($request->postData('role'));
        		if($role == 'member-pro')
        			$role = Role::ROLE_MEMBER_PRO;
        		else
        			$role = Role::ROLE_MEMBER;
				
        		if($mail == $mailConfirmation
        			&& $password == $passwordConfirmation
        			&& strlen($username) >= 6
        			&& strlen($password) >= 6)
        		{
        			$activationKey = mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
        			
        			$this->_user = new Users();
        			$this->_user->setUsername($username);
        			$this->_user->setMail($mail);
                                $this->_user->setMailingState($mailingState);
        			$this->_user->setPassword($password, Tipkin\Config::get('secret-key'));
        			$this->_user->setRoleId($role);
        			$this->_user->setIsMailVerified(false);
        			$this->_user->setActivationKey($activationKey);
        			
        			//Si le nom d'utilisateur n'existe pas
        			if(!$this->_userManager->isUsernameOrMailExist($username, $mail))
        			{
        				//Si le role est membre
        				if($this->_user->getRoleId() == Role::ROLE_MEMBER)
        				{
        					$this->_user->setIsActive(false);
        					$this->_userManager->save($this->_user);
        					
        					//Envoi mail recapitularif
        					$mailMessage = new Mail();
        					$mailMessage->sendVerifyEmail($this->_user, $password);
        					
        					$this->app->user()->setFlash('verify-account-sent');
		        			$this->app->httpResponse()->redirect('/login');
			        		
        				}
        				//Si le role est autre chose (un membre pro en fait)
        				else
        				{
        					$this->_user->setIsActive(false);
        					$this->_userManager->save($this->_user);
        					
        					//Envoi mail recapitularif
        					$messageMail = new Mail();
		        			$messageMail->sendRegistrationProInfo($this->_user, $password);
		        			
		        			$messageMail->sendAccountProCreated($this->_user);
		        			
		        			$this->app->user()->setFlash('pro-created');
		        			$this->app->httpResponse()->redirect('/login');
        				}
        			}
        			//Sinon si le nom d'utilisateur existe
        			else
        			{
        				$message = 'Le nom d\'utilisateur ou l\'adresse mail que vous avez entré existe déjà !';
        				$message = MessageBox::Error($message);
        			}
        		}
        		//Si la vérification de connexion ne passe pas (pas de JS)
        		else
        		{
        			$message = 'Le formulaire n\'a pas pu être validé  !
        				<br /><br />
        				Certains champs sont incorrects. Veuillez activez le javascript pour plus d\'informations.';
        			$message = MessageBox::Error($message);
        		}
        	}
        	
        	$this->page->smarty()->assign('registrationMessage', $message);
        	$this->page->smarty()->assign('user', $this->_user);
        }
        
        public function executeDisconnect(HTTPRequest $request)
        {
        	$this->app->user()->setAuthenticated(false);
        	$this->app->user()->setAttribute('id', null);
        	$this->app->httpResponse()->deleteCookie('tipkin-id');
        	$this->app->httpResponse()->redirect('/');
        }
        
        public function executeRecoverPassword(HTTPRequest $request)
        {
        	$this->init();
        	$message = '';
        	
        	if($request->postExists('recover-password'))
        	{
        		$mail = htmlspecialchars($request->postData('mail'));
        		
        		$user = $this->_userManager->getByMail($mail);
        		
        		if(!is_null($user))
        		{
	        		$newPassword = Users::CreateNewPassword();
	        		$user->setPassword($newPassword, Tipkin\Config::get('secret-key'));
	        		$this->_userManager->save($user);
	        		
	        		$messageMail = new Mail();
	        		$messageMail->sendNewPassword($user, $newPassword);
	        		
	        		$message = MessageBox::Success('Un email avec vos nouveaux identifiants de connexion vient de vous être envoyé');
        		}
        		else
        		{
        			$message = MessageBox::Error('L\'email que vous avez fourni n\'existe pas dans notre base de données');
        		}
        	}
        	$this->page->smarty()->assign('message',$message);
        }
        
        public function executeValidEmail(HTTPRequest $request)
		{
			$this->_userManager = $this->managers->getManagerOf('users');
			
			//Si la clé d'activation de vérification de mail est présente
			if($request->getExists('activationKey') && strlen($request->getData('activationKey')) > 0)
			{
				$id 			= htmlspecialchars($request->getData('id'));
				$activationKey 	= htmlspecialchars($request->getData('activationKey'));
				
				$this->_user = $this->_userManager->get($id);
				
				if(is_null($this->_user))
				{
					$this->app->httpResponse()->redirect404();
					exit();
				}
				
				if($this->_user->getIsMailVerified())
				{
					$this->app->httpResponse()->redirect('/');
					exit();
				}
				
				if ($this->_user->getActivationKey() == $activationKey)
				{
					$this->_user->setIsMailVerified(true);
					$this->_user->setIsActive(true);
					$this->_userManager->save($this->_user);
					
					$messageMail = new Mail();
					$messageMail->sendRegistrationInfo($this->_user);
					
					$this->app->user()->setFlash('mail-verified');
					
					$this->app->httpResponse()->redirect('/login');
					exit();
				}
				else
				{
					$this->app->httpResponse()->redirect404();
					exit();
				}
			}
			else
			{
				$this->app->httpResponse()->redirect404();
				exit();
			}
		}
        
        public function executeFacebookConnect(HTTPRequest $request)
        {
        	$this->init();
        	
        	$message = '';
        	$this->page->smarty()->assign('usernameExist', 'false');
        	
            if ($request->postExists('mailfb'))
            {
            	$username 	= htmlspecialchars($request->postData('usernamefb'));
        		$mail 		= htmlspecialchars($request->postData('mailfb'));
        		
        		$this->page->smarty()->assign('username', $username);
        		$this->page->smarty()->assign('mail', $mail);
        		
        		$this->_user = $this->_userManager->getByMail($mail);
        		if(!is_null($this->_user))
        		{
        			if($this->_user->getRoleId() >= Role::ROLE_MEMBER && $this->_user->getIsActive())
                	{
	                    $this->app->user()->setAuthenticated(true);
	                    $this->app->user()->setAttribute('id', $this->_user->id());
		                    
	                    $this->authenticationRedirection();
                	}
                	else
                	{
                		$this->app->user()->setFlash('profile-disabled');
                		
                		$this->app->httpResponse()->redirect('/login');
	        			exit();
                	}
        		}
        		else
        		{
        			if($this->_userManager->isUsernameOrMailExist($username, $mail))
        			{
        				$message = 'Le nom d\'utilisateur <strong>'. $username .'</strong> existe déjà ! Veuillez en choisir un autre.';
	        			$message = MessageBox::Warning($message);
	
	        			$this->page->smarty()->assign('usernameExist', 'true');
	        			$this->page->smarty()->assign('mail', $mail);
        			
        			}
        		}
            }
            else
            {
            	$this->app->httpResponse()->redirect('/');
        		exit();
            }
            
        	if($request->postExists('register'))
        	{
        		$username 	= htmlspecialchars($request->postData('username'));
        		$mail 		= htmlspecialchars($request->postData('mailfb'));
        		$role		= htmlspecialchars($request->postData('role'));
        		
        		if($role == 'member-pro')
        			$role = Role::ROLE_MEMBER_PRO;
        		else
        			$role = Role::ROLE_MEMBER;
        		
        		
        		// Vu qu'on récupère le mail précdement on ne teste finalement que le username
        		if(!$this->_userManager->isUsernameOrMailExist($username, $mail))
        		{
        			
        			// Si ni le nom d'utilisateur ni l'email existe on crée l'utilistateur
	        		$this->_user = new Users();
	        		$newPassword = Users::CreateNewPassword();
	        		$this->_user->setUsername($username);
	        		$this->_user->setMail($mail);
	        		$this->_user->setIsMailVerified(true);
	        		$this->_user->setActivationKey('');
	        		$this->_user->setRoleId($role);
	        		$this->_user->setPassword($newPassword, Tipkin\Config::get('secret-key'));
	        		
	        		if($role == Role::ROLE_MEMBER)
	        		{
		        		$this->_user->setIsActive(1);
		        		
			        	$this->_userManager->save($this->_user);
	
			        	$messageMail = new Mail();
	        			$messageMail->sendRegistrationInfo($this->_user, $newPassword);
	        			
		        		$this->app->user()->setAuthenticated(true);
		                   $this->app->user()->setAttribute('id', $this->_user->id());
		        			
		        		$this->app->httpResponse()->redirect('/profile');
		        		exit();
	        		}
	        		else
	        		{
	        			$this->_user->setIsActive(false);
        				$this->_userManager->save($this->_user);
        					
        				//Envoi mail recapitularif
        				$messageMail = new Mail();
		        		$messageMail->sendRegistrationProInfo($this->_user, $newPassword);
		        			
		        		$this->app->user()->setFlash('pro-created');
		        		$this->app->httpResponse()->redirect('/login');
	        		}
        		}
        		else
        		{
        			$message = 'Le nom d\'utilisateur <strong>'. $username .'</strong> existe déjà ! Veuillez en choisir un autre.';
        			$message = MessageBox::Warning($message);

        			$this->page->smarty()->assign('usernameExist', 'true');
        			$this->page->smarty()->assign('mail', $mail);
        			
        			// Sinon on redirige vers la page de registration où il redéfinira son nom d'utilisateur pour se connecter
        		}
        	}

        	$this->page->smarty()->assign('connexionMessage', $message);
        }
        
        public function executePopupConnect(HTTPRequest $request)
        {
         	$this->init();
        	
         	$returnUrl = $request->getData('returnUrl');
         	$this->page->smarty()->assign('returnUrl', $returnUrl);
         	
        	if ($request->postExists('connect'))
            {
                $login 			= htmlspecialchars($request->postData('login'));
                $password 		= htmlspecialchars($request->postData('password'));
                $createCookie	= $request->postExists('create-cookie');
                
             	$this->_user = $this->_userManager->authenticate($login, $password);
             	
                if (!is_null($this->_user))
                {
                	if($this->_user->getRoleId() >= Role::ROLE_MEMBER && $this->_user->getIsActive())
                	{
	                    $this->app->user()->setAuthenticated(true);
	                    $this->app->user()->setAttribute('id', $this->_user->id());
	                    
	                    if($createCookie)
	                    {
	                    	//On crée un cookie expirant dans un mois
	                    	$this->app->httpResponse()->setCookie('tipkin-id',$this->_user->id(), time()+60*60*24*30);
	                    }
		                    
	                    $this->app->httpResponse()->redirect($returnUrl);
	                    exit();
                	}
                	else
                	{
                		$this->app->user()->setFlash('profile-disabled');
                		
                		$this->app->httpResponse()->redirect('/login');
	                    exit();
                	}
                }
                else
                {
                	$this->app->user()->setFlash('bad-login');
                	
                	$this->app->httpResponse()->redirect('/login');
	                exit();
                }
            }
        }
        
    	public function executeUsernameExists(HTTPRequest $request)
        {
        	$this->init();
        	
        	if($request->getExists('username'))
        	{
        		$username = htmlspecialchars($request->getData('username'));
        		$isUsernameExists = $this->_userManager->isUsernameOrMailExist($username, $username);
        		
        		$this->page->smarty()->assign('usernameExists', $isUsernameExists);
        	}
        	else
        	{
        		$this->app->httpResponse()->redirect404();
        		exit();
        	}
        }
        
        //Private Functions
        private function authenticationRedirection()
        {
        	if($this->app->user()->isAuthenticated())
        	{
        		$this->_user = $this->_userManager->get($this->app->user()->getAttribute('id'));
        		if($this->_user->getRoleId() == Role::ROLE_MEMBER_PRO)
        			$this->app->httpResponse()->redirect('/profile-pro');
        		else
	        		$this->app->httpResponse()->redirect('/profile');
	        		
	        	exit();
        	}
        }
        
        private function init()
        {
        	$this->_userManager = $this->managers->getManagerOf('users');
        	
        	$this->authenticationRedirection();
        	
        	$this->displayInfoMessage();
        }
        
	    private function displayInfoMessage()
		{
			$message = '';
			if($this->app->user()->hasFlash())
			{
				$message = $this->app->user()->getFlash();
				switch ($message)
				{
					case 'verify-account-sent':
					$message = 'Félicitations pour votre inscription !
								<br /><br />
								Afin de la valider, nous venons de vous envoyer un courriel de confirmation avec un lien.
								<br />
								Cet email automatique peut être filtré par votre messagerie, vérifiez qu\'il ne soit pas placé dans les messages indésirables.';
		        	$message = MessageBox::Success($message);
					break;
					
					case 'mail-verified':
					$message = 'Votre adresse email a été vérifiée avec succès.
								<br />
								Vous pouvez dès à présent vous connecter avec vos identifiants de connexion.';
		        	$message = MessageBox::Success($message);
					break;
					
					case 'pro-created':
					$message = 'Votre compte pro a été créé avec succès.
								<br />
								Vous recevrez prochainement un mail vous annoncant son activation.';
		        	$message = MessageBox::Success($message);
					break;
					
					case 'profile-disabled':
					$message = 'Votre compte n\'est pas actif.
                				<br /><br />
                				N\'hésitez pas à nous contacter pour plus de détails';
		        	$message = MessageBox::Error($message);
					break;
					
					case 'bad-login':
					$message = 'Le pseudo ou le mot de passe est incorrect';
		        	$message = MessageBox::Error($message);
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