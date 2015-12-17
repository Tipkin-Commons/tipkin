<?php

class Category extends Record 
{
	protected 	$name,
				$description,
				$parentCategoryId,
				$isRoot;
				
	// Catégorie "Divers" par défaut pour tri admin				
	const DEFAULT_CATEGORY = 9;
				
	public function isValid()
	{
		$isValid = true;
		
		if(empty($this->name))
			$isValid = false;
		
		return $isValid;
	}
	
	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $parentCategoryId
	 */
	public function getParentCategoryId() {
		return $this->parentCategoryId;
	}

	/**
	 * @return the $isRoot
	 */
	public function getIsRoot() {
		return $this->isRoot;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param field_type $parentCategoryId
	 */
	public function setParentCategoryId($parentCategoryId) {
		$this->parentCategoryId = $parentCategoryId;
	}

	/**
	 * @param field_type $isRoot
	 */
	public function setIsRoot($isRoot) {
		$this->isRoot = $isRoot;
	}

}

?>