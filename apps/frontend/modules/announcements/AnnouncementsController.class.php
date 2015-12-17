<?php

class AnnouncementsController extends BackController 
{
	protected $_user, $_userManager;
	protected $_categoriesManager;
	protected $_announcement, $_announcementsManager;
	protected $_announcementPricesManager;
	protected $_announcementUnavailabilitiesManager;
	protected $_addressManager ;
	protected $_profileManager;
	protected $_departmentsManager;
	
	protected $_pageState;
	protected $_listOfPriceFields  = array(
						'HalfDay'	=> 'price-half-day',
						'Day' 		=> 'price-day',
						'WeekEnd'	=> 'price-week-end',
						'Week'		=> 'price-week',
						'Fortnight'	=> 'price-fortnight'
						);
	protected $_listOfGroupsEndField = array(
						ContactGroups::USERS	 	=> 'users',
						ContactGroups::TIPPEURS 	=> 'tippeurs',
						ContactGroups::FRIENDS 		=> 'friends',
						ContactGroups::FAMILY 		=> 'family',
						ContactGroups::NEIGHBORS 	=> 'neighbors'
						);
	
	public function __construct(Application $app,$module, $action)
    {
		parent::__construct($app, $module, $action);
		
		$this->authenticationRedirection();
		
		$this->init();
		
		$this->displayInfoMessage();
    }
	
	public function executeIndex(HTTPRequest $request)
	{
		$announces = $this->_announcementsManager->getListOf($this->_user->id());
		
		$this->page->smarty()->assign('announces', $announces);
		$this->page->smarty()->assign('categoriesManager', $this->_categoriesManager);
		
		$this->_pageState = 'drafts';
		
		if($request->getExists('state'))
		{
			switch ($request->getData('state')) {
				case 'drafts':
				$this->_pageState = 'drafts';
				break;
				case 'validated':
				$this->_pageState = 'validated';
				break;
				case 'archived':
				$this->_pageState = 'archived';
				break;
				case 'pending':
				$this->_pageState = 'pending';
				break;
				case 'refused':
				$this->_pageState = 'refused';
				break;
				default:
					;
				break;
			}
		}
		
		$this->page->smarty()->assign('state', $this->_pageState);
	}
	
	public function executeNew(HTTPRequest $request)
	{
		$categories		= $this->_categoriesManager->getListOf();
		$departments 	= $this->_departmentsManager->getListOf();
		$addresses 		= $this->_addressManager->getListOf($this->_user->id());
		$profile 		= $this->_profileManager->getByUserId($this->_user->id());
		
		$announce = new Announcement();
		$announce->setStateId(AnnouncementStates::STATE_DRAFT);
		
		if($request->postExists('title'))
		{
			$this->parseAnnounceForm($request, $announce);
			if($this->isAnnounceValid($announce))
			{
				$announce->setStateId(AnnouncementStates::STATE_DRAFT);
				$this->_announcementsManager->save($announce);
				
				$this->app->httpResponse()->redirect('/announcements/edit/photo/' . $announce->id());
				exit();
			}
			else
			{
				$this->app->user()->setFlash('draft-incomplete');
				$this->displayInfoMessage();	
			}
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('categories', $categories);
		$this->page->smarty()->assign('addresses', $addresses);
		$this->page->smarty()->assign('profile', $profile);
	}
	
	public function executeEditPhoto(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('submit-form'))
		{
			//On créé le répertoire s'il n'exsite pas
			MediaImage::createAnnounceDirectory($announce);
			
			$this->parsePhoto($request, $announce);
			
			$this->_announcementsManager->save($announce);
			
			$this->app->httpResponse()->redirect('/announcements/edit/prices/' . $announce->id());
			exit();
		}
		
		$this->page->smarty()->assign('announce', $announce);
	}
	
	public function executeEditPrices(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		$announcementPriceList = array();
		$announcementPriceList = $this->_announcementPricesManager->getByAnnouncementId($announceId);
		if(count($announcementPriceList) == 0)
		{
			$announcementPriceList = $this->initAnnouncementPriceArray();
		}
		
		if($request->postExists('submit-form'))
		{
			$caution 		= htmlspecialchars($request->postData('caution'));
			$isFullDayPrice = $request->postExists('is-full-day-price');
			
			$announce->setCaution($caution);
			$announce->setIsFullDayPrice($isFullDayPrice);
			
			$this->_announcementPricesManager->deleteByAnnouncementId($announce->id());		
			
			$announcementPriceList = $this->parsePrices($request, $announcementPriceList);
			foreach ($announcementPriceList as $announcementPrice) 
			{
				if($announcementPrice->getContactGroupId() == ContactGroups::USERS)
				{
					$announce->setPricePublic($announcementPrice->getDay());
				}
				
				$announcementPrice->setId(null);
				$announcementPrice->setAnnouncementId($announce->id());
				
				$this->_announcementPricesManager->save($announcementPrice);
			}
			
			$this->_announcementsManager->save($announce);
			
			$this->app->httpResponse()->redirect('/announcements/edit/calendar/' . $announce->id());
			exit();
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('announcePriceList', $announcementPriceList);
	}
	
	public function executeEditCalendar(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if(is_null($announce->getPricePublic()))
		{
			$this->app->httpResponse()->redirect('/announcements/edit/prices/' . $announce->id());
			exit();
		}
		
		if($request->postExists('submit-form'))
		{
			$this->parseCalendar($request, $announce);
			
			$this->_announcementsManager->save($announce);
			
			$this->app->httpResponse()->redirect('/announcements/edit/indisponibilities/' . $announce->id());
			exit();
		}
		
		$this->page->smarty()->assign('announce', $announce);
	}
	
	public function executeEditIndisponibilities(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if(is_null($announce->getPublicationDate()) || is_null($announce->getEndPublicationDate()))
		{
			$this->app->httpResponse()->redirect('/announcements/edit/calendar/' . $announce->id());
			exit();
		}
		
		$announcementUnavailabilities = array();
		$announcementUnavailabilities = $this->_announcementUnavailabilitiesManager->getByAnnouncementId($announceId);
		
		if($request->postExists('submit-form'))
		{
			$this->_announcementUnavailabilitiesManager->deleteByAnnouncementId($announce->id());
			
			$announcementUnavailabilities = $this->parseIndisponibilities($request, $announce);
			
			if(is_array($announcementUnavailabilities))
			{
				foreach ($announcementUnavailabilities as $unavailability) 
				{
					$unavailability->setAnnouncementId($announce->id());
					
					$this->_announcementUnavailabilitiesManager->save($unavailability);
				}
			}
			
			//Traitement et redirection en fonction de l'état de l'annonce
			//Si enregistrement en tant que brouillon
			if($request->postData('save-as-draft') == 'yes')
			{
				$this->app->user()->setFlash('draft-updated');
				//$this->displayInfoMessage();
				
				$announce->setStateId(AnnouncementStates::STATE_DRAFT);
				$this->_announcementsManager->save($announce);
			
				$this->app->httpResponse()->redirect('/announcements/drafts');
				exit();
			}
			
			//Si brouillon ou refusé
			if($announce->getStateId() == AnnouncementStates::STATE_DRAFT || $announce->getStateId() == AnnouncementStates::STATE_REFUSED)
			{
				$this->app->user()->setFlash('announce-pending');
				//$this->displayInfoMessage();
				
				$announce->setStateId(AnnouncementStates::STATE_PENDING);
				$this->_announcementsManager->save($announce);
				
				$messageMail = new Mail();
				$messageMail->sendAnnouncePendingValidation($announce, $this->_userManager->get($announce->getUserId()));
			
				$this->app->httpResponse()->redirect('/announcements/pending');
				exit();
			}
			
			//Si archive
			if($announce->getStateId() == AnnouncementStates::STATE_ARCHIVED)
			{
				$this->app->user()->setFlash('announce-published');
				//$this->displayInfoMessage();
				
				$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
				$this->_announcementsManager->save($announce);
			
				$this->app->httpResponse()->redirect('/announcements/validated');
				exit();
			}
				
			//Default
			$this->_announcementsManager->save($announce);
			
			$this->app->httpResponse()->redirect('/announcements');
			exit();
		}
		
		$dateList = array();
		foreach ($announcementUnavailabilities as $unavailability) 
		{
			$dateList[] = $unavailability->getDate();
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('dateList', implode(',', $dateList));
		$this->page->smarty()->assign('unavailabilities', $announcementUnavailabilities);
	}
	
	public function executeEdit(HTTPRequest $request)
	{
		$categories		= $this->_categoriesManager->getListOf();
		$departments 	= $this->_departmentsManager->getListOf();
		$addresses 		= $this->_addressManager->getListOf($this->_user->id());
		$profile 		= $this->_profileManager->getByUserId($this->_user->id());
		
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('title'))
		{
			$this->parseAnnounceForm($request, $announce);
			if($this->isAnnounceValid($announce))
			{
				//$announce->setStateId(AnnouncementStates::STATE_DRAFT);
				$this->_announcementsManager->save($announce);
				
				MediaImage::createAnnounceDirectory($announce);
				
				$this->app->httpResponse()->redirect('/announcements/edit/photo/' . $announce->id());
			}
			else
			{
				$this->app->user()->setFlash('draft-incomplete');
				$this->displayInfoMessage();	
			}
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('categories', $categories);
		$this->page->smarty()->assign('addresses', $addresses);
		$this->page->smarty()->assign('profile', $profile);
	}
	
	public function executeDelete(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		
		if(is_null($announce) || ($announce->getStateId() !=  AnnouncementStates::STATE_DRAFT && $announce->getStateId() !=  AnnouncementStates::STATE_REFUSED && $announce->getStateId() !=  AnnouncementStates::STATE_PENDING))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('submit-form'))
		{
			//On supprime tous les prix existants
			$this->_announcementPricesManager->deleteByAnnouncementId($announce->id());
			//On supprime toutes les non-disponibilités existants
			$this->_announcementUnavailabilitiesManager->deleteByAnnouncementId($announce->id());
				
			$announceDir = $_SERVER['DOCUMENT_ROOT'] . Announcement::ANNOUNCEMENT_DIRECTORY . $announce->id();
			
			//On supprime le dossier de l'annonce où sont stocké les images
			if(file_exists($announceDir))
				rmdir($announceDir);
			
			//On supprime l'annonce
			$this->_announcementsManager->delete($announce->id());
				
			$this->app->user()->setFlash('announce-deleted');
				
			$this->app->httpResponse()->redirect('/announcements');
			exit();
		}
		
		$this->page->smarty()->assign('announce', $announce);
	}
	
	public function executeReadAdminComment(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		$announce = $this->_announcementsManager->get($announceId);
		
		$this->page->smarty()->assign('announce', $announce);
	}
	
	public function executeUnarchive(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if($request->postExists('submit-form'))
		{
			$this->parseCalendar($request, $announce);
			
			$this->_announcementsManager->save($announce);
			
			$this->app->httpResponse()->redirect('/announcements/unarchive/indisponibilities/' . $announce->id());
			exit();
		}
		
		$this->page->smarty()->assign('announce', $announce);
	}
	
	public function executeUnarchiveIndisponibilities(HTTPRequest $request)
	{
		$announceId = $request->getData('announceId');
		
		$announce = $this->_announcementsManager->get($announceId);
		if(is_null($announce))
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if ($announce->getUserId() != $this->_user->id())
		{
			$this->app->httpResponse()->redirect404();
			exit();
		}
		
		if(is_null($announce->getPublicationDate()) || is_null($announce->getEndPublicationDate()))
		{
			$this->app->httpResponse()->redirect('/announcements/unarchive/' . $announce->id());
			exit();
		}
		
		$announcementUnavailabilities = array();
		$announcementUnavailabilities = $this->_announcementUnavailabilitiesManager->getByAnnouncementId($announceId);
		
		if($request->postExists('submit-form'))
		{
			$this->_announcementUnavailabilitiesManager->deleteByAnnouncementId($announce->id());
			
			$announcementUnavailabilities = $this->parseIndisponibilities($request, $announce);
			
			foreach ($announcementUnavailabilities as $unavailability) 
			{
				$unavailability->setAnnouncementId($announce->id());
				
				$this->_announcementUnavailabilitiesManager->save($unavailability);
			}
			
			//Traitement et redirection en fonction de l'état de l'annonce
			
			//Si archive
			if($announce->getStateId() == AnnouncementStates::STATE_ARCHIVED)
			{
				$this->app->user()->setFlash('announce-published');
				$this->displayInfoMessage();
				
				$announce->setStateId(AnnouncementStates::STATE_VALIDATED);
				$this->_announcementsManager->save($announce);
			
				$this->app->httpResponse()->redirect('/announcements/validated');
				exit();
			}
		}
		
		$dateList = array();
		foreach ($announcementUnavailabilities as $unavailability) 
		{
			$dateList[] = $unavailability->getDate();
		}
		
		$this->page->smarty()->assign('announce', $announce);
		$this->page->smarty()->assign('dateList', implode(',', $dateList));
		$this->page->smarty()->assign('unavailabilities', $announcementUnavailabilities);
	}
	
	private function isAnnounceValid(Announcement $announce)
	{
		$isValid = true;
		
		$title 			= $announce->getTitle();
		$description 	= $announce->getDescription();
		
		$categoryId		= $announce->getCategoryId();
		$subCategoryId	= $announce->getSubCategoryId();
		
		$address1		= $announce->getAddress1();
		$zipCode		= $announce->getZipCode();
		$city			= $announce->getCity();
		
		if(empty($title) || empty($description) || empty($categoryId) || 
			empty($subCategoryId) || empty($address1) || empty($zipCode) ||
			empty($city))
		{
			$isValid = false;
		}
		
		return $isValid;
	}
	
	private function parseAnnounceForm(HTTPRequest $request, Announcement $announce)
	{
		$title 				= htmlspecialchars($request->postData('title'));
		$description		= htmlspecialchars($request->postData('description'));
		if($request->postExists('has-tips'))
			$tips			= htmlspecialchars($request->postData('tips'));
		else 
			$tips			= '';
			
		$rawMaterial		= htmlspecialchars($request->postData('raw-material'));
		
		$address1			= htmlspecialchars($request->postData('address1'));
		$address2			= htmlspecialchars($request->postData('address2'));
		$zipCode			= htmlspecialchars($request->postData('zip-code'));
		$city				= htmlspecialchars($request->postData('city'));
		$country			= 'FRANCE';
		
		$departmentId	= htmlspecialchars($request->postData('department'));
		$regionId 		= $this->_departmentsManager->get($departmentId)->getRegionId();
		
		$categoryId			= htmlspecialchars($request->postData('category'));
		$subCategoryId		= htmlspecialchars($request->postData('sub-category'));
		$userId 			= $this->_user->id();
		
		$announce->setTitle($title);
		$announce->setDescription($description);
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
	}
	
	private function parsePhoto(HTTPRequest $request, Announcement $announce)
	{
		ini_set("memory_limit",'512M');
		
		if($request->postExists('delete-photo-main'))
		{
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . $announce->getPhotoMain());
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . Announcement::THUMBNAILS_PREFIX . $announce->getPhotoMain());
			
			$announce->setPhotoMain('');
		}
		
		if($request->postExists('delete-photo-option-1'))
		{
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . $announce->getPhotoOption1());
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . Announcement::THUMBNAILS_PREFIX . $announce->getPhotoOption1());
			
			$announce->setPhotoOption1('');
		}
		
		if($request->postExists('delete-photo-option-2'))
		{
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . $announce->getPhotoOption2());
			unlink(MediaImage::getAnnounceDirectory($announce) . '/' . Announcement::THUMBNAILS_PREFIX . $announce->getPhotoOption2());
			
			$announce->setPhotoOption2('');
		}
		
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
	
	private function savePhoto(Announcement $announce, $target ,$file)
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
				
				$filename = $target . '-' . time() . '.jpg';
				$thumbnails = Announcement::THUMBNAILS_PREFIX . $filename;
				
				$simpleImage->save(MediaImage::getAnnounceDirectory($announce) . '/' . $filename);
				$thumbnailsSimpleImage->save(MediaImage::getAnnounceDirectory($announce) . '/' . $thumbnails);
				
				$getMethod = 'get'.$target;
				$setMethod = 'set'.$target;
				
				if($announce->$getMethod() != Announcement::IMAGE_DEFAULT && $announce->$getMethod() != '')
				{
						unlink(MediaImage::getAnnounceDirectory($announce) . '/' . $announce->$getMethod());
						unlink(MediaImage::getAnnounceDirectory($announce) . '/' . Announcement::THUMBNAILS_PREFIX . $announce->$getMethod());
				}
				
				$announce->$setMethod($filename);
			}
		}
	}
	
	private function initPrices()
	{
		$announcementPriceList = array();
		foreach ($this->_listOfGroupsEndField as $contactGroup => $value) 
		{
			$prices = new AnnouncementPrice();
				
			$prices->setContactGroupId($contactGroup);
			
			$this->page->smarty()->assign('price'.ucfirst($value), $prices);
		}
	}
	
	private function parsePrices(HTTPRequest $request, $announcementPriceList)
	{
		$announcementPriceListReturn = array();				
						
		foreach ($this->_listOfGroupsEndField as $contactGroup => $endField) 
		{
			$currentAnnouncementPrice = new AnnouncementPrice();
			$currentAnnouncementPrice->setContactGroupId($contactGroup);
			
			foreach ($announcementPriceList as $announcementPrice) 
			{
				if($announcementPrice->getContactGroupId() == $contactGroup)
					$currentAnnouncementPrice = $announcementPrice;
			}
			
			if($request->postExists('price-default-for-'.$endField) && $contactGroup != ContactGroups::USERS)
				$currentAnnouncementPrice->setIsActive(false);
			else
				$currentAnnouncementPrice->setIsActive(true);
				
			foreach ($this->_listOfPriceFields as $classAttribute => $formField) 
			{
				$setMethod 	= 'set'.$classAttribute;
				$value		=  htmlspecialchars($request->postData($formField . '-' . $endField));
				
				$currentAnnouncementPrice->$setMethod($this->str2num($value));
			}
			
			$announcementPriceListReturn[] = $currentAnnouncementPrice;
		}
		
		return $announcementPriceListReturn;
	}
	
	private function parseCalendar(HTTPRequest $request, Announcement $announce)
	{
		$dateIntervalOneDay 	= new DateInterval('P1D');
		$dateIntervalSixMonth 	= new DateInterval('P2Y');
		
		$publicationDate = new DateTime();
		$publicationDate->add($dateIntervalOneDay);
		
		if($request->postData('publication-date-radio') == 'manual')
		{
			$date = $request->postData('publication-date');
			$date = DateTime::createFromFormat('j/m/Y', $date);
			
			$publicationDate = $date;
		}
		
		$announce->setPublicationDate($publicationDate->format('Y-m-d'));
		
		$endPublicationDate = $publicationDate;
		$endPublicationDate->add($dateIntervalSixMonth);
		
		if($request->postData('end-publication-date-radio') == 'manual')
		{
			$date = $request->postData('end-publication-date');
			$date = DateTime::createFromFormat('j/m/Y', $date);
			
			$endPublicationDate = $date;
		}
		
		$announce->setEndPublicationDate($endPublicationDate->format('Y-m-d'));
	}
	
	private function parseIndisponibilities(HTTPRequest $request, Announcement $announce)
	{
		if($request->postData('date-list') != '')
		{
			$unavailabilities = array();
			
			$dates = $request->postData('date-list');
			$dates = explode(',', $dates);
			
			foreach ($dates as $date)
			{
				$unavailability = new AnnouncementUnavailability();
				
				$unavailability->setDate($date);
				$unavailability->setDateOption($request->postData($date));
				$unavailability->setAnnouncementId($announce->id());

				$unavailabilities[] = $unavailability;
			}
			
			return $unavailabilities;
		}
		
		return null;
	}
	
	
	private function initAnnouncementPriceArray()
	{
		$announcementPriceArray = array();
		foreach ($this->_listOfGroupsEndField as $group => $field)
		{
			$price = new AnnouncementPrice();
			$price->setContactGroupId($group);
			
			$announcementPriceArray[] = $price;
		}
		return $announcementPriceArray;
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
	
		$this->_userManager 							= $this->managers->getManagerOf('users');
		$this->_announcementsManager 					= $this->managers->getManagerOf('announcements');
		$this->_announcementPricesManager 				= $this->managers->getManagerOf('announcementprices');
		$this->_announcementUnavailabilitiesManager 	= $this->managers->getManagerOf('announcementunavailabilities');
		$this->_categoriesManager 						= $this->managers->getManagerOf('categories');
		$this->_addressManager	 						= $this->managers->getManagerOf('addresses');
		$this->_profileManager 							= $this->managers->getManagerOf('profiles');
		$this->_departmentsManager 						= $this->managers->getManagerOf('departments');
		
		$userId = $this->app->user()->getAttribute('id');
		
		//Initialisation de variables
		$this->_user = $this->_userManager->get($userId);
		
		
		
		if($this->_user->getRoleId() == Role::ROLE_MEMBER_PRO)
		{
			if($this->action == 'new')
			{
				$this->app->httpResponse()->redirect('/announcements-pro' . '/new');
				exit();
			}
			else
			{
				$this->app->httpResponse()->redirect('/profilepro');
				exit();
			}
		}
		
		if($this->_profileManager->getByUserId($this->_user->id()) == null)
		{
			$this->app->httpResponse()->redirect('/profile');
			exit();
		}
	}
	
	private function authenticationRedirection()
    {
    	if($this->app->user()->isAdminAuthenticated())
    	{
    		$this->app->user()->setAuthenticated(true);
    	}
    	
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
				
				case 'announce-pending':
				$message = 'Votre nouvelle annonce a été créée et est en attente de validation !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-created':
				$message = 'Votre nouvelle annonce a été créée et enregistrée dans vos brouillons !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-incomplete':
				$message = 'Votre annonce est incomplète, veuillez remplir les champs obligatoire !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'refused-incomplete':
				$message = 'Votre annonce refusée a bien été enregistrée mais reste incomplète pour une validation !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'announce-updated':
				$message = 'Votre annonce a été mise à jour !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'draft-updated':
				$message = 'Votre brouillon d\'annonce a été mis à jour !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'refused-updated':
				$message = 'Votre annonce refusée a été mise à jour !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-deleted':
				$message = 'Votre annonce a été supprimée !';
	        	$message = MessageBox::Success($message);
				break;
				
				case 'announce-incomplete':
				$message = 'Votre annonce a bien été enregistrée en tant que brouillon car incomplète pour une publication !';
	        	$message = MessageBox::Warning($message);
				break;
				
				case 'announce-unsave':
				$message = 'Votre annonce n\'a pas été enregistrée car elle est incomplète !';
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