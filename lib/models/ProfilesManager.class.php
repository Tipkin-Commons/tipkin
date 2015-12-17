<?php

abstract class ProfilesManager extends Manager 
{
/**
	 * Méthode permettant d'ajouter un profil
	 * @param $profile Le profil à ajouter
	 * @return void
	 */
	abstract protected function add(Profile $profile);
	
	/**
	 * Méthode permettant de supprimer un profil
	 * @param $id L'identifiant du profil à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	/**
	 * Méthode permettant de supprimer un profil
	 * @param $userId L'identifiant de l'utilisateur du profil à supprimer
	 * @return void
	 */
	abstract public function deleteByUserId($userId);
	
	/**
	 * Méthode permettant d'enregistrer un profil
	 * @param $profile Le profil à enregistrer
	 * @return void
	 */
	public function save(Profile $profile)
	{
		if($profile->isValid())
		{
			$profile->isNew() ? $this->add($profile) : $this->modify($profile);
		}
		else
		{
			throw new RuntimeException('Le profil doit être valide pour être enregistré');
		}
	}
	
	/**
	 * Méthode permettant de modifier un profil
	 * @param $profile Le profil à modifier
	 * @return void
	 */
	abstract protected function modify(Profile $profile);
	
	/**
	 * Méthode permettant de récupérer une liste de profil
	 * @return array
	 */
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer un profil spécifique
	 * @param $id Identifiant du profil
	 * @return Profile
	 */
	abstract public function get($id);
	
	/**
	 * Méthode permettant de récupérer un profil via l'identifiant de l'utilisateur
	 * @param int $userId
	 * @return Profile
	 */
	abstract public function getByUserId($userId);
}
?>