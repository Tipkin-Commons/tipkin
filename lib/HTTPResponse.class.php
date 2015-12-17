<?php
    class HTTPResponse extends ApplicationComponent
    {
        protected $page;
        
        public function addHeader($header)
        {
            header($header);
        }
        
        public function redirect($location)
        {
            header('Location: ' . $location); 
            exit;
        }
        
        public function redirect404()
        {
            $this->page = new Page($this->app);
            
            $this->page->smarty()->force_compile = true;
            
            $this->addHeader('HTTP/1.0 404 Not Found');
            
            $this->page->addTemplateDir(dirname(__FILE__). '/../apps/' . $this->app->name() . '/templates/');
            $this->page->addTemplateDir(dirname(__FILE__).'/../errors/');
            
            if($this->app->name() == 'backend')
            {
	            $this->page->smarty()->assign('isAdminAuthenticate', 'false');
	            if($this->app()->user()->isAdminAuthenticated())
	            	$this->page->smarty()->assign('isAdminAuthenticate', 'true');
            }
            else
            {
	            $this->page->smarty()->assign('isAuthenticate', 'false');
	            if($this->app()->user()->isAuthenticated())
	            {
	            	$userManager	= new UsersManager_PDO(PDOFactory::getMysqlConnexion());
	            	$user = $userManager->get($this->app->user()->getAttribute('id'));
	            	
	            	$this->page->smarty()->assign('isAuthenticate', 'true');
	            	$this->page->smarty()->assign('currentUser', $user);
	            }
            }

            $this->page->smarty()->assign('title', 'Erreur 404');
            
            $this->page->smarty()->display('404.tpl');
            
        }
        
        public function setPage(Page $page)
        {
            $this->page = $page;
        }
        
        // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
        public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
        {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        }
        
        public function deleteCookie($name)
        {
        	unset($_COOKIE[$name]);
        	setcookie($name, NULL, -1);
        }
    }
?>
