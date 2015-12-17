<?php
class AddressesController extends BackController 
{
	protected $_user;
	protected $_address;
	protected $_profile;
	
	protected $_userManager;
	protected $_addressManager;
	protected $_profileManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);

		$this->authenticationRedirection();
		$this->init();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$addresses = $this->_addressManager->getListOf($this->_user->id());
		
		$this->page->smarty()->assign('mainAddress', $this->_address);
		$this->page->smarty()->assign('addresses', $addresses);
	}
	
	public function executeAdd(HTTPRequest $request)
	{		
		$address =  new Address();
		$this->page->smarty()->assign('address', $address);
		
		if($request->postExists('save-address'))
		{
			$this->parseForm($request, $address);
			$this->_addressManager->save($address);
			
			$this->app->httpResponse()->redirect('/addresses');
			exit();
		}
	}
	
	public function executeEdit(HTTPRequest $request)
	{
		$address =  new Address();
		
		if($this->app->httpRequest()->getExists('addressId'))
		{
			$addressId = htmlspecialchars($this->app->httpRequest()->getData('addressId'));
			$address = $this->_addressManager->get($addressId);
			
			if(is_null($address))
			{
				$this->app->httpResponse()->redirect('/addresses');
				exit();
			}
		}
		else
		{
			$this->app->httpResponse()->redirect('/addresses');
			exit();
		}
		
		$this->page->smarty()->assign('address', $address);
		
		if($request->postExists('save-address'))
		{
			$this->parseForm($request, $address);
			$this->_addressManager->save($address);
			
			$this->app->httpResponse()->redirect('/addresses');
			exit();
		}
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$address =  new Address();
		
		if($this->app->httpRequest()->getExists('addressId'))
		{
			$addressId = htmlspecialchars($this->app->httpRequest()->getData('addressId'));
			
			if($addressId != $this->_profile->getMainAddressId())
				$address = $this->_addressManager->delete($addressId);
		}

		$this->app->httpResponse()->redirect('/addresses');
	}
	
	private function parseForm(HTTPRequest $request, Address $address)
	{
		$title			= htmlspecialchars($request->postData('title'));
		$address1		= htmlspecialchars($request->postData('address-1'));
		$address2		= htmlspecialchars($request->postData('address-2'));
		$zipCode		= htmlspecialchars($request->postData('zip-code'));
		$city			= htmlspecialchars($request->postData('city'));
		$country		= 'France';
		
		$address->setTitle($title);
		$address->setAddress1($address1);
		$address->setAddress2($address2);
		$address->setZipCode($zipCode);
		$address->setCity($city);
		$address->setCountry($country);
		$address->setUserId($this->app->user()->getAttribute('id'));
	}
	
	private function authenticationRedirection()
    {
    	if(!$this->app->user()->isAuthenticated() && $this->app->httpRequest()->cookieExists('tipkin-id'))
        {
        	$this->app->user()->setAttribute('id', $this->app->httpRequest()->cookieData('tipkin-id')) ;
        	$this->app->user()->setAuthenticated(true);
        }
        
    	if(!$this->app->user()->isAuthenticated())
    	{
        	$this->app->httpResponse()->redirect('/login');
        	exit();
    	}
	}
	
	private function init()
	{
		//Initialisation des managers
		$this->_userManager 	= $this->managers->getManagerOf('users');
		$this->_addressManager 	= $this->managers->getManagerOf('addresses');
		$this->_profileManager 	= $this->managers->getManagerOf('profiles');
		
		$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_userManager->get($userId);
		
		$this->_profile = $this->_profileManager->getByUserId($userId);
		
		if(!is_null($this->_user))
			$this->_address = $this->_addressManager->get($this->_profile->getMainAddressId());
		else 
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
	}
}

?>