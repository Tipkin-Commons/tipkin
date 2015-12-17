<?php

class Announcement extends Record 
{
	protected 	$title,
				$description,
				$pricePublic,
				$isFullDayPrice,
				$caution,
				$photoMain,
				$photoOption1,
				$photoOption2,
				$tips,
				$rawMaterial,
				$address1,
				$address2,
				$zipCode,
				$city,
				$country,
				$regionId,
				$departmentId,
				$isPublished,
				$publicationDate,
				$endPublicationDate,
				$categoryId,
				$subCategoryId,
				$userId,
				$stateId,
				$adminComment,
				$refAnnouncementId;

	const IMAGE_DEFAULT = '/images/announce.png';
	const THUMBNAILS_PREFIX	= 'thumb_';
	const ANNOUNCEMENT_DIRECTORY = '/announcements/';
				
	/**
	 * @return the $publicationDate
	 */
	public function getPublicationDate() {
		return $this->publicationDate;
	}

	/**
	 * @param field_type $publicationDate
	 */
	public function setPublicationDate($publicationDate) {
		$this->publicationDate = $publicationDate;
	}

	/**
	 * @return the $regionId
	 */
	public function getRegionId() {
		return $this->regionId;
	}

	/**
	 * @return the $departmentId
	 */
	public function getDepartmentId() {
		return $this->departmentId;
	}

	/**
	 * @param field_type $regionId
	 */
	public function setRegionId($regionId) {
		$this->regionId = $regionId;
	}

	/**
	 * @param field_type $departmentId
	 */
	public function setDepartmentId($departmentId) {
		$this->departmentId = $departmentId;
	}

	/**
	 * @return the $endPublicationDate
	 */
	public function getEndPublicationDate() {
		return $this->endPublicationDate;
	}

	/**
	 * @param field_type $endPublicationDate
	 */
	public function setEndPublicationDate($endPublicationDate) {
		$this->endPublicationDate = $endPublicationDate;
	}

	/**
	 * @return the $subCategoryId
	 */
	public function getSubCategoryId() {
		return $this->subCategoryId;
	}

	/**
	 * @param field_type $subCategoryId
	 */
	public function setSubCategoryId($subCategoryId) {
		$this->subCategoryId = $subCategoryId;
	}

	/**
	 * @return the $isFullDayPrice
	 */
	public function getIsFullDayPrice() {
		return $this->isFullDayPrice;
	}

	/**
	 * @param field_type $isFullDayPrice
	 */
	public function setIsFullDayPrice($isFullDayPrice) {
		$this->isFullDayPrice = $isFullDayPrice;
	}

	/**
	 * @return the $caution
	 */
	public function getCaution() {
		return $this->caution;
	}

	/**
	 * @param field_type $caution
	 */
	public function setCaution($caution) {
		$this->caution = $caution;
	}

	/**
	 * @return the $refAnnouncementId
	 */
	public function getRefAnnouncementId() {
		return $this->refAnnouncementId;
	}

	/**
	 * @param field_type $refAnnouncementId
	 */
	public function setRefAnnouncementId($refAnnouncementId) {
		$this->refAnnouncementId = $refAnnouncementId;
	}

	/**
	 * @return the $city
	 */
	public function getCity() {
		return strtoupper($this->city);
	}

	/**
	 * @param field_type $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	public function isValid()
	{
		$isValid = true;
		
		if
		(
			empty($this->title) ||
			empty($this->userId)
		)
			$isValid = false;
			
		return $isValid;
	}
	
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $link
	 */
	public function getLink($id) {
		$clean = $this->title;
		$clean= strtr(utf8_decode($clean),
		utf8_decode(
		'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
		'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
		
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

		return $id."/".$clean."-a-la-location" ;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $pricePublic
	 */
	public function getPricePublic() {
		return $this->pricePublic;
	}

	/**
	 * @return the $photoMain
	 */
	public function getPhotoMain() {
		if(empty($this->photoMain))
			return self::IMAGE_DEFAULT;
		
		return $this->photoMain;
	}

	/**
	 * @return the $photoOption1
	 */
	public function getPhotoOption1() {
		return $this->photoOption1;
	}

	/**
	 * @return the $photoOption2
	 */
	public function getPhotoOption2() {
		return $this->photoOption2;
	}

	/**
	 * @return the $tips
	 */
	public function getTips() {
		return $this->tips;
	}

	/**
	 * @return the $rawMaterial
	 */
	public function getRawMaterial() {
		return $this->rawMaterial;
	}

	/**
	 * @return the $address1
	 */
	public function getAddress1() {
		return $this->address1;
	}

	/**
	 * @return the $address2
	 */
	public function getAddress2() {
		return $this->address2;
	}

	/**
	 * @return the $zipCode
	 */
	public function getZipCode() {
		return $this->zipCode;
	}

	/**
	 * @return the $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return the $isPublished
	 */
	public function getIsPublished() {
		return $this->isPublished;
	}

	/**
	 * @return the $categoryId
	 */
	public function getCategoryId() {
		return $this->categoryId;
	}

	/**
	 * @return the $userId
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return the $stateId
	 */
	public function getStateId() {
		return $this->stateId;
	}

	/**
	 * @return the $adminComment
	 */
	public function getAdminComment() {
		return $this->adminComment;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param field_type $pricePublic
	 */
	public function setPricePublic($pricePublic) {
		$this->pricePublic = $pricePublic;
	}

	/**
	 * @param field_type $photoMain
	 */
	public function setPhotoMain($photoMain) {
		if($photoMain != self::IMAGE_DEFAULT)
			$this->photoMain = $photoMain;
	}

	/**
	 * @param field_type $photoOption1
	 */
	public function setPhotoOption1($photoOption1) {
		$this->photoOption1 = $photoOption1;
	}

	/**
	 * @param field_type $photoOption2
	 */
	public function setPhotoOption2($photoOption2) {
		$this->photoOption2 = $photoOption2;
	}

	/**
	 * @param field_type $tips
	 */
	public function setTips($tips) {
		$this->tips = $tips;
	}

	/**
	 * @param field_type $rawMaterial
	 */
	public function setRawMaterial($rawMaterial) {
		$this->rawMaterial = $rawMaterial;
	}

	/**
	 * @param field_type $address1
	 */
	public function setAddress1($address1) {
		$this->address1 = $address1;
	}

	/**
	 * @param field_type $address2
	 */
	public function setAddress2($address2) {
		$this->address2 = $address2;
	}

	/**
	 * @param field_type $zipCode
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}

	/**
	 * @param field_type $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * @param field_type $isPublished
	 */
	public function setIsPublished($isPublished) {
		$this->isPublished = $isPublished;
	}

	/**
	 * @param field_type $categoryId
	 */
	public function setCategoryId($categoryId) {
		$this->categoryId = $categoryId;
	}

	/**
	 * @param field_type $userId
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
	}

	/**
	 * @param field_type $stateId
	 */
	public function setStateId($stateId) {
		$this->stateId = $stateId;
	}

	/**
	 * @param field_type $adminComment
	 */
	public function setAdminComment($adminComment) {
		$this->adminComment = $adminComment;
	}
	
	public function copy(Announcement $object)
    {   
        foreach (get_object_vars($object) as $key => $value) {
            $this->$key = $value;
        }
    }   

}

?>
