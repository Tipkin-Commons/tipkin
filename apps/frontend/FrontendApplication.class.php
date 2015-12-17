<?php
    require_once dirname(__FILE__).'/../../lib/autoload.php';
    
    class FrontendApplication extends Application
    {
        public function __construct()
        {
            parent::__construct();
            
            $this->name = 'frontend';
        }
        
        public function run()
        {
        	if(Tipkin\Config::get('maintenance-mode') == 'on')
        	{
        		$this->httpResponse->redirect('/maintenance.html');
        		exit();
        	}
        	
            $router = new Router($this);
            
            $controller = $router->getController();
            if(!is_null($controller))
            {
            	$controller->execute();
            	$this->httpResponse->setPage($controller->page());
            }
        }
    }
?>
