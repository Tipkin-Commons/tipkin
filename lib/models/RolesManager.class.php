<?php

abstract class RolesManager extends Manager 
{
	/**
	 * Méthode permettant de récupérer une liste de rôles
	 * @return array
	 */
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer un rôle spécifique
	 * @param $id Identifiant du rôle
	 * @return Role
	 */
	abstract public function get($id);
}

?>