<?php
    require_once dirname(__FILE__).'/../../lib/autoload.php';
    
    class BackendApplication extends Application
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->name = 'backend';
        }
        
        public function run()
        {
            if ($this->user->isAdminAuthenticated())
            {
                $router = new Router($this);
                $controller = $router->getController();
            }
            else
            {
                require dirname(__FILE__).'/modules/connexion/ConnexionController.class.php';
                $controller = new ConnexionController($this, 'connexion', 'index');
            }
            
        	if(!is_null($controller))
            {
            	$controller->execute();
            	$this->httpResponse->setPage($controller->page());
            }
        }
    }
?>