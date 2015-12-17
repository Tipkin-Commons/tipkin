<?php

class SearchController extends BackController 
{
	protected $_categoriesManager;
	protected $_regionsManager;
	protected $_departmentsManager;
	protected $_filterManager;
	protected $_profilesManager;
	protected $_profilesProManager;
	protected $_usersManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->init();
    }
    
    public function executeIndex(HTTPRequest $request)
    {
    	$announceFilter = new AnnounceFilter();
		
		if($request->postExists('search'))
		{
			$this->parseSearch($request, $announceFilter);
			
			$url = '/search/page='		 . '0'
										 . '/region=' 		. $announceFilter->getRegionId() 
										 . '/department='	. $announceFilter->getDepartmentId()
										 . '/category='		. $announceFilter->getCategoryId()
										 . '/subcategory='	. $announceFilter->getSubCategoryId()
										 . '/zipcode='		. $announceFilter->getZipCode()
										 . '/community='	. $announceFilter->getInCommunity()
										 . '/filter='		. $announceFilter->getFilterText();
			
			$this->app->httpResponse()->redirect($url);
			exit();
		}
		
		$categories 	= $this->_categoriesManager->getListOf();
		$regions 		= $this->_regionsManager->getListOf();
		$departments 	= $this->_departmentsManager->getListOf();
		
		$this->assignFilter($request, $announceFilter);
		
		$announcements 		= $this->_filterManager->getAnnouncement($announceFilter);
		$announcementsPro 	= $this->_filterManager->getAnnouncementPro($announceFilter);
		
		$this->page->smarty()->assign('announcements', $announcements);
		$this->page->smarty()->assign('announcementsPro', $announcementsPro);
		
		$this->page->smarty()->assign('profilesManager', $this->_profilesManager);
		$this->page->smarty()->assign('profilesProManager', $this->_profilesProManager);
		$this->page->smarty()->assign('usersManager', $this->_usersManager);
		$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
		$this->page->smarty()->assign('regionsManager', $this->_regionsManager);
		$this->page->smarty()->assign('departmentsManager', $this->_departmentsManager);
		
		$this->page->smarty()->assign('categories', $categories);
		$this->page->smarty()->assign('regions', $regions);
		$this->page->smarty()->assign('departments', $departments);
    }
    
    private function parseSearch(HTTPRequest $request, AnnounceFilter $announceFilter)
    {
    	$regionId 		= htmlspecialchars($request->postData('region'));
    	$departmentId 	= htmlspecialchars($request->postData('department'));
    	$categoryId 	= htmlspecialchars($request->postData('category'));
    	$subCategoryId 	= htmlspecialchars($request->postData('subcategory'));
		$zipCode	 	= htmlspecialchars($request->postData('zip-code'));
    	
    	$filterText		= htmlspecialchars(urldecode($request->postData('filter')));
    	$filterText		= preg_replace('/(\/|\+)/'	, ' '	, $filterText);
    	//Supprime les espaces inutiles
    	$filterText 	= preg_replace('/\s\s+/'	, ' '	, $filterText);
    	
    	$previousFilterText = htmlspecialchars($request->postData('previous-filter-text'));
    	$previousFilterText	= preg_replace('/(\/|\+)/'	, ' '	, $previousFilterText);
    	//Supprime les espaces inutiles
    	$previousFilterText	= preg_replace('/\s\s+/'	, ' '	, $previousFilterText);
    	
		$inCommunity	= htmlspecialchars($request->postData('community-filter'));
		if ( $previousFilterText != $filterText )
			$inCommunity = null;
		 
		
		if(!empty($inCommunity))
			$inCommunity = $this->app->user()->getAttribute('id');
    	
    	$announceFilter->setRegionId($regionId);
    	$announceFilter->setDepartmentId($departmentId);
    	$announceFilter->setCategoryId($categoryId);
    	$announceFilter->setSubCategoryId($subCategoryId);
		$announceFilter->setZipCode($zipCode);
    	$announceFilter->setFilterText($filterText);
    	$announceFilter->setInCommunity($inCommunity);
    }
    
    private function assignFilter(HTTPRequest $request, AnnounceFilter $announceFilter)
    {
    	$regionId 		= htmlspecialchars($request->getData('regionId'));
    	$departmentId 	= htmlspecialchars($request->getData('departmentId'));
    	$categoryId 	= htmlspecialchars($request->getData('categoryId'));
    	$subCategoryId 	= htmlspecialchars($request->getData('subCategoryId'));
		$zipCode	 	= htmlspecialchars($request->getData('zipCode'));
    	
    	$filterText		= htmlspecialchars(urldecode($request->getData('filter')));
    	$filterText		= preg_replace('/(\/|\+)/'	, ' '	, $filterText);
    	//Supprime les espaces inutiles
    	$filterText 	= preg_replace('/\s\s+/'	, ' '	, $filterText);
    	
    	$announceFilter->setRegionId($regionId);
    	$announceFilter->setDepartmentId($departmentId);
    	$announceFilter->setCategoryId($categoryId);
    	$announceFilter->setSubCategoryId($subCategoryId);
		$announceFilter->setZipCode($zipCode);
    	$announceFilter->setFilterText($filterText);
    	
    	$inCommunity	= htmlspecialchars($request->getData('community'));
    	if(!empty($inCommunity))
    		$inCommunity = $this->app->user()->getAttribute('id');
    	
    	$announceFilter->setInCommunity($inCommunity);
    	
    	
    	$this->page->smarty()->assign('filter', $announceFilter);
    }
    
	private function init()
    {
    	$this->_categoriesManager 	= $this->managers->getManagerOf('categories');
		$this->_regionsManager 		= $this->managers->getManagerOf('regions');
		$this->_departmentsManager 	= $this->managers->getManagerOf('departments');
		$this->_filterManager 		= $this->managers->getManagerOf('announcefilter');
		$this->_profilesManager		= $this->managers->getManagerOf('profiles');
		$this->_profilesProManager	= $this->managers->getManagerOf('profilespro');
		$this->_usersManager		= $this->managers->getManagerOf('users');
    }
}

?>