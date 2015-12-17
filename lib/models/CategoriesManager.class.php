<?php

abstract class CategoriesManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter une catégorie
	 * @param $category La catégorie à ajouter
	 * @return void
	 */
	abstract protected function add(Category $category);
	
	/**
	 * Méthode permettant de supprimer une catégorie
	 * @param $id L'identifiant de la catégorie à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	abstract public function deleteByParentCategoryId($parentCategoryId);
	
	/**
	 * Méthode permettant d'enregistrer une catégorie
	 * @param $category La catégorie à enregistrer
	 * @return void
	 */
	public function save(Category $category)
	{
		if($category->isValid())
		{
			$category->isNew() ? $this->add($category) : $this->modify($category);
		}
		else
		{
			throw new RuntimeException('La catégorie doit être valide pour être enregistrée');
		}
	}
	
	/**
	 * Méthode permettant de modifier une catégorie
	 * @param $category La catégorie à modifier
	 * @return void
	 */
	abstract protected function modify(Category $category);
	
	/**
	 * Méthode permettant de récupérer une liste d'catégories
	 * @param $userId Identifiant de l'utilisateur sur lequelle on veut récupérer des catégories
	 * @return array
	 */
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer une catégorie spécifique
	 * @param $id Identifiant de l'catégorie
	 * @return Category
	 */
	abstract public function get($id);
	
	abstract public function hasAnnouncementsLinked($categoryId);
}

?>