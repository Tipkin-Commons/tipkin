<?php

class AnnounceFilter 
{
	protected $regionId,
			  $departmentId,
			  $categoryId,
			  $subCategoryId,
			  $zipCode,
			  $inCommunity,
			  $filterText,
			  $limitStart,
			  $limitNbRows,
			  $countResult;
	
	/**
	 * @return the $zipCode
	 */
	public function getZipCode() {
		return $this->zipCode;
	}

	/**
	 * @param field_type $zipCode
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}
	
	/**
	 * @return the $inCommunity
	 */
	public function getInCommunity() {
		return $this->inCommunity;
	}
	
	/**
	 * @param field_type $inCommunity
	 */
	public function setInCommunity($inCommunity) {
		$this->inCommunity = $inCommunity;
	}
	
	/**
	 * @return the $countResult
	 */
	public function getCountResult() {
		return $this->countResult;
	}

	/**
	 * @param field_type $countResult
	 */
	public function setCountResult($countResult) {
		$this->countResult = $countResult;
	}

	/**
	 * @return the $limitStart
	 */
	public function getLimitStart() {
		return $this->limitStart;
	}

	/**
	 * @return the $limitNbRows
	 */
	public function getLimitNbRows() {
		return $this->limitNbRows;
	}

	/**
	 * @param field_type $limitStart
	 */
	public function setLimitStart($limitStart) {
		$this->limitStart = $limitStart;
	}

	/**
	 * @param field_type $limitNbRows
	 */
	public function setLimitNbRows($limitNbRows) {
		$this->limitNbRows = $limitNbRows;
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
	 * @return the $categoryId
	 */
	public function getCategoryId() {
		return $this->categoryId;
	}

	/**
	 * @return the $subCategoryId
	 */
	public function getSubCategoryId() {
		return $this->subCategoryId;
	}

	/**
	 * @return the $filterText
	 */
	public function getFilterText() {
		return $this->filterText;
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
	 * @param field_type $categoryId
	 */
	public function setCategoryId($categoryId) {
		$this->categoryId = $categoryId;
	}

	/**
	 * @param field_type $subCategoryId
	 */
	public function setSubCategoryId($subCategoryId) {
		$this->subCategoryId = $subCategoryId;
	}

	/**
	 * @param field_type $filterText
	 */
	public function setFilterText($filterText) {
		$this->filterText = $filterText;
	}
	
}

?>