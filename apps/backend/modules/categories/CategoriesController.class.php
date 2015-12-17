<?php

class CategoriesController extends BackController 
{
	protected $_userManager;
	protected $_categoriesManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
    
    public function executeIndex(HTTPRequest $request)
    {
    	$this->page->smarty()->assign('usersManager', $this->_userManager);
    	$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
    }
    
    public function executeAdd(HTTPRequest $request)
    {
    	$category = new Category();
    	
    	if($request->postExists('submit-form'))
    	{
    		$this->parseForm($request, $category);
    		$this->_categoriesManager->save($category);
    		
    		$this->app->user()->setFlash('category-created');
    		$this->app->httpResponse()->redirect('/admin/categories');
    		exit();
    	}
    	
    	$categoryType = $request->getData('categoryType');
    	$this->page->smarty()->assign('category', $category);
    	$this->page->smarty()->assign('categoryType', $categoryType);
    	$this->page->smarty()->assign('actionPost', '/admin/categories/add/' . $categoryType);
    	$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
    }
    
 	public function executeEdit(HTTPRequest $request)
    {
    	$category = $this->_categoriesManager->get($request->getData('categoryId'));
    	
    	if($request->postExists('submit-form'))
    	{
    		$this->parseForm($request, $category);
    		$this->_categoriesManager->save($category);
    		
    		$this->app->user()->setFlash('category-updated');
    		$this->app->httpResponse()->redirect('/admin/categories');
    		exit();
    	}
    	
    	if($category->getIsRoot())
    		$categoryType = 'category';
    	else
    		$categoryType = 'sub-category';
    	$this->page->smarty()->assign('category', $category);
    	$this->page->smarty()->assign('categoryType', $categoryType);
    	$this->page->smarty()->assign('actionPost', '/admin/categories/edit/' . $category->id());
    	$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
    }
	
    public function executeDelete(HTTPRequest $request)
    {
    	$category = $this->_categoriesManager->get($request->getData('categoryId'));
    	
    	if($request->postExists('submit-form'))
    	{
    		$this->_categoriesManager->deleteByParentCategoryId($category->id());
    		$this->_categoriesManager->delete($category->id());
    		
    		$this->app->user()->setFlash('category-deleted');
    		$this->app->httpResponse()->redirect('/admin/categories');
    		exit();
    	}
    	
    	if($category->getIsRoot())
    		$categoryType = 'category';
    	else
    		$categoryType = 'sub-category';
    	$this->page->smarty()->assign('category', $category);
    	$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
    }
    
    private function parseForm(HTTPRequest $request, Category $category)
    {
    	$name = htmlspecialchars($request->postData('name'));
    	$description = htmlspecialchars($request->postData('description'));
    	$isRoot = !$request->postExists('parent-category');
    	
    	$category->setName($name);
    	$category->setIsRoot($isRoot);
    	$category->setDescription($description);
    	
    	if(!$isRoot)
    	{
    		$parentCategoryId = $request->postData('parent-category');
    		$category->setParentCategoryId($parentCategoryId);
    	}
    }
    
	private function authenticationRedirection()
	{
		if(!$this->app->user()->isAdminAuthenticated())
		{
        	$this->app->httpResponse()->redirect('/admin/');
        	exit();
        }
	}
        
    private function init()
    {
        $this->_userManager 							= $this->managers->getManagerOf('users');
        $this->_categoriesManager	 					= $this->managers->getManagerOf('categories');
        
        $this->_admin = $this->_userManager->get($this->app->user()->getAttribute('admin-id'));

        if($this->_admin->getRoleId() < Role::ROLE_ADMINISTRATEUR)
        {
        	$this->app->httpResponse()->redirect('/admin/../');
        	exit();
        }
	}
	
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'category-created':
				$message = 'Nouvelle catégorie créée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'category-updated':
				$message = 'Catégorie modifiée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'category-deleted':
				$message = 'Catégorie supprimée !';
	        	$message = MessageBox::Success($message);
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