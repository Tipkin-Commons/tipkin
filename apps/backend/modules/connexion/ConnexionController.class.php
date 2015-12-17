<?php
    class ConnexionController extends BackController
    {
    	protected $_user;
    	protected $_userManager;
    	
        public function executeIndex(HTTPRequest $request)
        {
        	$this->init();
        	
        	if ($request->postExists('connect'))
            {
                $login = htmlspecialchars($request->postData('login'));
                $password = htmlspecialchars($request->postData('password'));                
                
             	$this->_user = $this->_userManager->authenticate($login, $password);
             	
                if (!is_null($this->_user) 
                	&& $this->_user->getRoleId() >= Role::ROLE_ADMINISTRATEUR 
                	&& $this->_user->getIsActive())
                {
                    $this->app->user()->setAdminAuthenticated(true);
                    $this->app->user()->setAttribute('admin-id', $this->_user->id());

                    
                    $this->authenticationRedirection();
                }
                else
                {
                	$message = MessageBox::Error('L\'authentification a échoué !');                	
                    
                	$this->page->smarty()->assign('connexionMessage', $message);
                }
            }
        }
        
        public function executeDisconnect(HTTPRequest $request)
        {
        	$this->app->user()->setAdminAuthenticated(false);
        	$this->app->httpResponse()->redirect('/admin/.');
        }
        
    	private function authenticationRedirection()
        {
        	if($this->app->user()->isAdminAuthenticated())
        	{
        		$this->_user = $this->_userManager->get($this->app->user()->getAttribute('admin-id'));
        		$this->app->httpResponse()->redirect('/admin/users');
        		exit();
        	}
        }
        
        private function init()
        {
        	$this->_userManager = $this->managers->getManagerOf('users');
        	
        	$this->authenticationRedirection();
        }
    }
?>