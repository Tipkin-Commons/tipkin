<?php
    class Page extends ApplicationComponent
    {
        protected $smarty;
        protected $templateDirectories;
        
        public function __construct()
        {
            $this->smarty = new Smarty();
            $this->smarty->addPluginsDir(dirname(__FILE__).'/smarty_plugins');
        	$this->templatesDirectories = array();
        }
        
        public function smarty()
        {
        	return $this->smarty;
        }
        
    	public function setSmarty($smartyInstance)
        {
        	$this->smarty = $smartyInstance;
        }
        
        public function clearTemplateDir()
        {
        	$this->templateDirectories = array();
        }
        
        public function addTemplateDir($directory)
        {
        	$this->templateDirectories[] = $directory;
        	$this->smarty->setTemplateDir($this->templateDirectories);
        }
    }
?>
