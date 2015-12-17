<?php
    class Router extends ApplicationComponent
    {
        public function getController()
        {
            $dom = new DOMDocument;
            $dom->load(dirname(__FILE__).'/../apps/'.$this->app->name().'/config/routes.xml');
            
            foreach ($dom->getElementsByTagName('route') as $route)
            {
                if (preg_match('`^'.$route->getAttribute('url').'$`', $this->app->httpRequest()->requestURI(), $matches))
                {
                    $module = $route->getAttribute('module');
                    $action = $route->getAttribute('action');
                    
                    $classname = ucfirst($module).'Controller';
                    $file = dirname(__FILE__).'/../apps/'.$this->app->name().'/modules/'.$module.'/'.$classname.'.class.php';
					
                    if (!file_exists($file))
                    {
                       throw new RuntimeException('Le module où pointe la route n\'existe pas');
                    }
                    
                    require dirname(__FILE__).'/../apps/'.$this->app->name().'/modules/'.$module.'/'.$classname.'.class.php';
                    
                    $controller = new $classname($this->app, $module, $action);
                    
                    if ($route->hasAttribute('vars'))
                    {
                        $vars = explode(',', $route->getAttribute('vars'));
                        
                        foreach ($matches as $key => $match)
                        {
                            if ($key !== 0)
                            {
                                $this->app->httpRequest()->addGetVar($vars[$key - 1], $match);
                            }
                        }
                    }
                    
                    break;
                }
            }
            
            if (!isset($controller))
            {
                $this->app->httpResponse()->redirect404();
            }
            else
	            return $controller;
        }
    }
?>