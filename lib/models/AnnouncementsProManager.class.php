<?php

abstract class AnnouncementsProManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter une addresse
	 * @param $announcementPro L'annonce pro à ajouter
	 * @return void
	 */
	abstract protected function add(AnnouncementPro $announcementPro);
	
	/**
	 * Méthode permettant de supprimer une annonce pro
	 * @param $id L'identifiant de l'annonce pro à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	/**
	 * Méthode permettant de supprimer un profil
	 * @param $userId L'identifiant de l'utilisateur des annonces à supprimer
	 * @return void
	 */
	abstract public function deleteByUserId($userId);
	
	/**
	 * Méthode permettant d'enregistrer une annonce pro
	 * @param $announcementPro L'annonce pro à enregistrer
	 * @return void
	 */
	public function save(AnnouncementPro $announcementPro)
	{
		if($announcementPro->isValid())
		{
			$announcementPro->isNew() ? $this->add($announcementPro) : $this->modify($announcementPro);
		}
		else
		{
			throw new RuntimeException('L\'annonce pro doit être valide pour être enregistrée');
		}
	}
	
	/**
	 * Méthode permettant de modifier une annonce pro
	 * @param $announcementPro L'annonce pro à modifier
	 * @return void
	 */
	abstract protected function modify(AnnouncementPro $announcementPro);
	
	/**
	 * Méthode permettant de récupérer une liste d'annonce pros
	 * @param $userId Identifiant de l'utilisateur sur lequelle on veut récupérer des annonces pro
	 * @return array
	 */
	abstract public function getListOf($userId);
	
	/**
	 * Méthode permettant de récupérer une annonce pro spécifique
	 * @param $id Identifiant de l'annonce pro
	 * @return AnnouncementPro
	 */
	abstract public function get($id);
}

?>