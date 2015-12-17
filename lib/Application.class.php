<?php
    abstract class Application
    {
        protected $httpRequest;
        protected $httpResponse;
        protected $name;
        protected $user;
        
        public function __construct()
        {
			date_default_timezone_set("Europe/Paris");
            $this->httpRequest = new HTTPRequest($this);
            $this->httpResponse = new HTTPResponse($this);
            $this->user = new User($this);
            
            $this->name = '';
        }
        
        abstract public function run();
        
        public function httpRequest()
        {
            return $this->httpRequest;
        }
        
        public function httpResponse()
        {
            return $this->httpResponse;
        }
        
        public function name()
        {
            return $this->name;
        }
        
        public function user()
        {
            return $this->user;
        }
    }
?>
