<?php

class AnnouncementsproController extends BackController 
{
	protected $_user, $_userManager;
	protected $_categoriesManager;
	protected $_departmentsManager;
	protected $_announcementPro, $_announcementsProManager;
	protected $_addressManager;
	protected $_profileProManager;
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$announces = $this->_announcementsProManager->getListOf($this->_user->id());
		
		$this->page->smarty()->assign('announces', $announces);
		$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
		
		$state = 'drafts';
		
		if($request->getExists('state'))
		{
			switch ($request->getData('state')) {
				case 'drafts':
				$state = 'drafts';
				break;
				case 'validated':
				$state = 'validated';
				break;
				case 'archived':
				$state = 'archived';
				break;
				default:
					;
				break;
			}
		}
		
		$this->page->smarty()->assign('state', $state);
	}
	
	public function executeNew(HTTPRequest $request)
	{
		$announces 		= $this->_announcementsProManager->getListOf($this->_user->id());
		$categories 	= $this->_categoriesManager->getListOf();
		$addresses 		= $this->_addressManager->getListOf($this->_user->id());
		$profile 		= $this->_profileProManager->getByUserId($this->_user->id());
		$departments 	= $this->_departmentsManager->getListOf();
		
		$announce = new AnnouncementPro();
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('categories', $categories);
		$this->page->smarty()->assign('addresses', $addresses);
		$this->page->smarty()->assign('profile', $profile);
		$this->page->smarty()->assign('departments', $departments);
		
		if($request->postExists('title'))
		{
			$this->parseForm($request, $announce);
			
			$state = '';
			
			$announce->setStateId(AnnouncementStates::STATE_DRAFT);
			$this->_announcementsProManager->save($announce);
			
			mkdir($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id());
			chmod($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id(), 0755);
			
			$this->parsePhoto($request, $announce);
			$this->_announcementsProManager->save($announce);
			
			if($request->postExists('state-validated') && $this->isAnnouncementValid($announce))
			{
				$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
				
				if($announce->getIsPublished())
				{
					$currentDate = new DateTime();
					$announce->setPublicationDate($currentDate->format('Y-m-d'));
				}
				
				$state = 'validated';
				
				$this->app->user()->setFlash('announce-created');
			}
			else 
			{
				$announce->setStateId(AnnouncementStates::STATE_DRAFT);
				
				if($request->postExists('state-validated'))
					$this->app->user()->setFlash('announce-incomplete');
				else
					$this->app->user()->setFlash('draft-created');
			}
			
			$this->_announcementsProManager->save($announce);
			
			if($request->postData('action') == 'save')
			{
				$this->app->httpResponse()->redirect('/announcements-pro' . '/edit/' . $announce->id());
				exit();	
			}
			else 
			{
				$this->app->httpResponse()->redirect('/announcements-pro' . '/' . $state);
				exit();
			}
		}
	}
	
	public function executeEdit(HTTPRequest $request)
	{
		$announces = $this->_announcementsProManager->getListOf($this->_user->id());
		$categories = $this->_categoriesManager->getListOf();
		$addresses = $this->_addressManager->getListOf($this->_user->id());
		$departments 	= $this->_departmentsManager->getListOf();
		
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsProManager->get($announceId);
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('categories', $categories);
		$this->page->smarty()->assign('addresses', $addresses);
		$this->page->smarty()->assign('departments', $departments);
		
		if($request->postExists('title'))
		{
		
			$this->parseForm($request, $announce);
			
			$this->parsePhoto($request, $announce);
			
			$state = '';
			
			$saveAnnounce = true;
			
			//Si nouvelle annonce ou brouillon
			if($announce->getStateId() == AnnouncementStates::STATE_DRAFT || $announce->getStateId() == null)
			{
				if($request->postExists('state-validated') && $this->isAnnouncementValid($announce))
				{
					if($announce->getIsPublished())
					{
						$currentDate = new DateTime();
						$announce->setPublicationDate($currentDate->format('Y-m-d'));
					}
					$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
						
					$state = 'validated';
					
					$this->app->user()->setFlash('announce-updated');
				}
				else 
				{
					$state = 'drafts';
					
					if($request->postExists('state-validated'))
						$this->app->user()->setFlash('draft-incomplete');
					else
						$this->app->user()->setFlash('draft-updated');
				}
			}
			// Si non brouillon
			else 
			{
				if($this->isAnnouncementValid($announce))
				{
					if($announce->getStateId() ==  AnnouncementStates::STATE_VALIDATED)
					{
						$state = 'validated';
					}
					else 
					{
						$state = 'archived';
					}
					
					$this->app->user()->setFlash('announce-updated');
				}
				else 
				{
					$state = 'drafts';
					
					$this->app->user()->setFlash('announce-unsave');
					
					$saveAnnounce = false;
				}
			}
			
			if($saveAnnounce)
			{
				$this->_announcementsProManager->save($announce);
			
				if($request->postData('action') == 'save')
				{
					$this->app->httpResponse()->redirect('/announcements-pro' . '/edit/' . $announce->id());
					exit();	
				}
				else 
				{
					$this->app->httpResponse()->redirect('/announcements-pro' . '/' . $state);
					exit();
				}
			}
			else
			{
				$this->displayInfoMessage();
			}
		}
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsProManager->get($announceId);
		$this->page->smarty()->assign('announce', $announce);
		
		if($request->postExists('confirm'))
		{
			$this->_announcementsProManager->delete($announce->id());
			
			$announceDir = $_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id();
			
			if(file_exists($announceDir))
				rmdir($announceDir);

			$this->app->user()->setFlash('announce-deleted');
			
			$this->app->httpResponse()->redirect('/announcements-pro');
			exit();
		}		
	}
	
	public function executePublish(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		$announce = $this->_announcementsProManager->get($announceId);
		
		if($this->isAnnouncementValid($announce))
		{	
			$announce->setIsPublished(true);
			$currentDate = new DateTime();
			$announce->setPublicationDate($currentDate->format('Y-m-d'));
			
			$this->_announcementsProManager->save($announce);
			
			$this->app->user()->setFlash('announce-published');
		}
		
		$this->app->httpResponse()->redirect('/announcements-pro' . '/validated');
		exit();
	}
	
	public function executeUnpublish(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		$announce = $this->_announcementsProManager->get($announceId);
		
		if($this->isAnnouncementValid($announce))
		{
			$announce->setIsPublished(false);
			$this->_announcementsProManager->save($announce);
			
			$this->app->user()->setFlash('announce-unpublished');
		}
		
		$this->app->httpResponse()->redirect('/announcements-pro' . '/validated');
		exit();
	}
	
	public function executePreview(HTTPRequest $request)
	{
		$announces = $this->_announcementsProManager->getListOf($this->_user->id());
		$categories = $this->_categoriesManager->getListOf();
		
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsProManager->get($announceId);
		
		$profileManager = $this->managers->getManagerOf('profilespro');
		$profile = $profileManager->getByUserId($this->_user->id());
		
		$addressManager = $this->managers->getManagerOf('addresses');
		$address = $addressManager->get($profile->getMainAddressId());
		
		$this->page->smarty()->assign('profile', $profile);
		$this->page->smarty()->assign('mainAddress', $address);
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('categories', $categories);
	}
	
	public function executeArchive(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsProManager->get($announceId);
		$this->page->smarty()->assign('announce', $announce);
		
		if($request->postExists('confirm'))
		{
			$announce->setStateId(AnnouncementStates::STATE_ARCHIVED);
			$this->_announcementsProManager->save($announce);
						
			$this->app->httpResponse()->redirect('/announcements-pro' . '/archived');
			exit();
		}		
	}
	
	public function executeUnarchive(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsProManager->get($announceId);
		$this->page->smarty()->assign('announce', $announce);
		
		if($request->postExists('confirm'))
		{
			$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
			
			$currentDate = new DateTime();
			$announce->setPublicationDate($currentDate->format('Y-m-d'));
			
			$this->_announcementsProManager->save($announce);
						
			$this->app->httpResponse()->redirect('/announcements-pro' . '/validated');
			exit();
		}		
	}
	
	private function isAnnouncementValid(AnnouncementPro $announce)
	{
		$isValid = true;
		
		$address1 	= $announce->getAddress1();
		$zipCide 	= $announce->getZipCode();
		$city		= $announce->getCity();
		
		if(empty($address1) || empty($zipCide) || empty($city))
		{
			$isValid = false;
		}
		
		$description 	= $announce->getDescription();
		$photoMain 		= $announce->getPhotoMain();
		$pricePublic	= $announce->getPricePublic();
		
		if(empty($description)  || $pricePublic ==  0)
		{
			$isValid = false;
		}
		
		return $isValid;
	}
	
	private function parseForm(HTTPRequest $request, AnnouncementPro $announce)
	{
		$title 			= htmlspecialchars($request->postData('title'));
		$isPublished	= $request->postExists('is-published');
		$description	= htmlspecialchars($request->postData('description'));
		$pricePublic	= htmlspecialchars($request->postData('price-public'));

		if($request->postExists('has-tips'))
			$tips			= htmlspecialchars($request->postData('tips'));
		else 
			$tips			= '';
			
		$rawMaterial	= htmlspecialchars($request->postData('raw-material'));
		
		$address1		= htmlspecialchars($request->postData('address1'));
		$address2		= htmlspecialchars($request->postData('address2'));
		$zipCode		= htmlspecialchars($request->postData('zip-code'));
		$city			= htmlspecialchars($request->postData('city'));
		$country		= 'FRANCE';
		
		$departmentId	= htmlspecialchars($request->postData('department'));
		$regionId 		= $this->_departmentsManager->get($departmentId)->getRegionId();
		
		$categoryId		= htmlspecialchars($request->postData('category'));
		$subCategoryId	= htmlspecialchars($request->postData('sub-category'));
		$userId 		= $this->_user->id();
		
		//Parsing
		
		$announce->setTitle($title);
		$announce->setIsPublished($isPublished);
		$announce->setDescription($description);
		$announce->setPricePublic($this->str2num($pricePublic));
		$announce->setTips($tips);
		$announce->setRawMaterial($rawMaterial);
		
		$announce->setAddress1($address1);
		$announce->setAddress2($address2);
		$announce->setZipCode($zipCode);
		$announce->setCity($city);
		$announce->setCountry($country);
		
		$announce->setDepartmentId($departmentId);
		$announce->setRegionId($regionId);
		
		$announce->setCategoryId($categoryId);
		$announce->setSubCategoryId($subCategoryId);
		$announce->setUserId($userId);
		
		$announce->setAdminComment('');
		
		// Demande de suppression de photo
		if($request->postExists('delete-photo-main'))
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $announce->getPhotoMain());
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . AnnouncementPro::THUMBNAILS_PREFIX . $announce->getPhotoMain());
			
			$announce->setPhotoMain('');
		}
		
		if($request->postExists('delete-photo-option-1'))
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $announce->getPhotoOption1());
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . AnnouncementPro::THUMBNAILS_PREFIX . $announce->getPhotoOption1());
			
			$announce->setPhotoOption1('');
		}
		
		if($request->postExists('delete-photo-option-2'))
		{
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $announce->getPhotoOption2());
			unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . AnnouncementPro::THUMBNAILS_PREFIX . $announce->getPhotoOption2());
			
			$announce->setPhotoOption2('');
		}
	}
	
	private function parsePhoto(HTTPRequest $request, AnnouncementPro $announce)
	{
		ini_set("memory_limit",'512M');
		
		if($request->fileExists('photo-main'))
		{
			$photoMain = $request->fileData('photo-main');
			$this->savePhoto($announce, 'PhotoMain', $photoMain);
		}
		
		if($request->fileExists('photo-option-1'))
		{
			$photoOption1 = $request->fileData('photo-option-1');
			$this->savePhoto($announce, 'PhotoOption1', $photoOption1);
		}
	
		if($request->fileExists('photo-option-2'))
		{
			$photoOption2 = $request->fileData('photo-option-2');
			$this->savePhoto($announce, 'PhotoOption2', $photoOption2);
		}
	}
	
	private function savePhoto(AnnouncementPro $announce, $target ,$file)
	{
		if($file['error'] == 0)
		{	
			$simpleImage = new SimpleImage();
			$thumbnailsSimpleImage = new SimpleImage();
			
			$simpleImage->load($file['tmp_name']);
			$thumbnailsSimpleImage->load($file['tmp_name']);
			
			if(!is_null($simpleImage->image_type))
			{
				$height = $simpleImage->getHeight();
				$width = $simpleImage->getWidth();
				
				//Redimensionnement de l'image original en format modéré
				if($height > 1200 || $width > 1600)
				{
					if($height > $width)
						$simpleImage->resizeToHeight(1200);
					else 
						$simpleImage->resizeToWidth(1600);
				}
				
				//Redimensionnement de l'image original en miniature
				if($height > $width)
					$thumbnailsSimpleImage->resizeToHeight(300);
				else 
					$thumbnailsSimpleImage->resizeToWidth(300);
				
				$filename = $target. '-' . time() . '.jpg';
				$thumbnails = AnnouncementPro::THUMBNAILS_PREFIX . $filename;
				
				$simpleImage->save($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $filename);
				$thumbnailsSimpleImage->save($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $thumbnails);
				
				$getMethod = 'get'.$target;
				$setMethod = 'set'.$target;
				
				if($announce->$getMethod() != AnnouncementPro::IMAGE_DEFAULT && $announce->$getMethod() != '')
				{
						unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . $announce->$getMethod());
						unlink($_SERVER['DOCUMENT_ROOT'] . AnnouncementPro::ANNOUNCEMENT_PRO_DIRECTORY . $announce->id() . '/' . AnnouncementPro::THUMBNAILS_PREFIX . $announce->$getMethod());
				}
				
				$announce->$setMethod($filename);
			}
		}
	}
	
	private function str2num($str)
	{ 
		if(strpos($str, '.') < strpos($str,',')){ 
	    	$str = str_replace('.','',$str); 
	        $str = strtr($str,',','.');            
        } 
        else
        { 
            $str = str_replace(',','',$str);            
        }
		
        $str = round((float)$str, 2);
        
        return $str; 
	}
	
	private function init()
	{
	
		$this->_userManager 			= $this->managers->getManagerOf('users');
		$this->_announcementsProManager = $this->managers->getManagerOf('announcementspro');
		$this->_categoriesManager 		= $this->managers->getManagerOf('categories');
		$this->_addressManager 			= $this->managers->getManagerOf('addresses');
		$this->_profileProManager	 	= $this->managers->getManagerOf('profilespro');
		$this->_departmentsManager 		= $this->managers->getManagerOf('departments');
		
		$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_userManager->get($userId);
		
		if($this->_user->getRoleId() != Role::ROLE_MEMBER_PRO)
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($this->_profileProManager->getByUserId($this->_user->id()) == null)
		{
			$this->app->httpResponse()->redirect('/profile-pro');
			exit();
		}
	}
	
	private function authenticationRedirection()
    {
    	if(!$this->app->user()->isAuthenticated() && $this->app->httpRequest()->cookieExists('tipkin-id'))
        {
        	$this->app->user()->setAttribute('id', $this->app->httpRequest()->cookieData('tipkin-id')) ;
        	$this->app->user()->setAuthenticated(true);
        }
        	
    	if(!$this->app->user()->isAuthenticated())
        	$this->app->httpResponse()->redirect('/login');
	}
	
	private function displayInfoMessage()
	{
		$message = '';
		if($this->app->user()->hasFlash())
		{
			switch ($this->app->user()->getFlash()) 
			{
				case 'announce-created':
				$message = 'Votre nouvelle annonce a été créée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-created':
				$message = 'Votre nouvelle annonce a été créée et enregistrée dans vos brouillons !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-incomplete':
				$message = 'Votre brouillon d\'annonce a bien été enregistré mais reste incomplet pour une publication !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'announce-updated':
				$message = 'Votre annonce a été mise à jour !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-updated':
				$message = 'Votre brouillon d\'annonce a été mise à jour !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-deleted':
				$message = 'Votre annonce a été supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-incomplete':
				$message = 'Votre annonce a bien été enregistré en tant que brouillon car incomplète pour une publication !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'announce-unsave':
				$message = 'Votre annonce n\'a pas été enregistré car elle est incomplète !';
	        	$message = MessageBox::Error($message);
				break;
				
				case 'announce-published':
				$message = 'Votre annonce vient d\'être publiée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-unpublished':
				$message = 'Votre annonce vient d\'être dépubliée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-archived':
				$message = 'Votre annonce a été archivée !';
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