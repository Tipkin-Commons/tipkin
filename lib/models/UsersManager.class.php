<?php
abstract class UsersManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter un utilisateur
	 * @param $user L'utilisateur à ajouter
	 * @return void
	 */
	abstract protected function add(Users $user);
	
	/**
	 * Méthode permettant de supprimer un utilisateur
	 * @param $id L'identifiant de l'utilisateur à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	/**
	 * Méthode permettant d'enregistrer un utilisateur
	 * @param $user L'utilisateur à enregistrer
	 * @return void
	 */
	public function save(Users $user)
	{
		if($user->isValid())
		{
			$user->isNew() ? $this->add($user) : $this->modify($user);
		}
		else
		{
			throw new RuntimeException('L\'utilisateur doit être valide pour être enregistré');
		}
	}
	
	/**
	 * Méthode permettant de modifier un utilisateur
	 * @param $user L'utilisateur à modifier
	 * @return void
	 */
	abstract protected function modify(Users $user);
	
	/**
	 * Méthode permettant de récupérer une liste d'utilisateur
	 * @param $roleId Identifiant du rôle sur lequelle on veut récupérer des utilisateurs
	 * @return array
	 */
	abstract public function getListOf($roleId);
	
	/**
	 * Méthode permettant de récupérer un utilisateur spécifique
	 * @param $id Identifiant de l'utilisateur
	 * @return User
	 */
	abstract public function get($id);
	
	/**
	 * Méthode permettant de récupérer un utilisateur spécifique
	 * @param $id Identifiant de l'utilisateur
	 * @return User
	 */
	abstract public function authenticate($login, $password);
	
	/**
	 * Methode permettant de vérifier l'existance d'un nom d'utilisateur
	 * @param $username Nom d'utilisateur
	 * @param $mail Email d'utilisateur
	 * @return bool
	 */
	abstract public function isUsernameOrMailExist($username, $mail);
	
	/**
	 * Méthode permettant de récupérer un utilisateur spécifique
	 * @param $mail Email de l'utilisateur
	 * @return User
	 */
	abstract public function getByMail($mail);
}

?>