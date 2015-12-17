<?php

abstract class ProfilesProManager extends Manager 
{
/**
	 * Méthode permettant d'ajouter un profil
	 * @param $profilePro Le profil à ajouter
	 * @return void
	 */
	abstract protected function add(ProfilePro $profilePro);
	
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
	 * @param $profilePro Le profil à enregistrer
	 * @return void
	 */
	public function save(ProfilePro $profilePro)
	{
		if($profilePro->isValid())
		{
			$profilePro->isNew() ? $this->add($profilePro) : $this->modify($profilePro);
		}
		else
		{
			throw new RuntimeException('Le profil doit être valide pour être enregistré');
		}
	}
	
	/**
	 * Méthode permettant de modifier un profil
	 * @param $profilePro Le profil à modifier
	 * @return void
	 */
	abstract protected function modify(ProfilePro $profilePro);
	
	/**
	 * Méthode permettant de récupérer une liste de profil
	 * @return array
	 */
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer un profil spécifique
	 * @param $id Identifiant du profil
	 * @return ProfilePro
	 */
	abstract public function get($id);
	
	/**
	 * Méthode permettant de récupérer un profil via l'identifiant de l'utilisateur
	 * @param int $userId
	 * @return ProfilePro
	 */
	abstract public function getByUserId($userId);
}
?>