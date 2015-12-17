<?php

abstract class CarrouselsManager extends Manager {
	/**
	 * Méthode permettant d'ajouter une addresse
	 * @param $address L'adresse à ajouter
	 * @return void
	 */
	abstract protected function add(Carrousel $carrousel);
	
	/**
	 * Méthode permettant de supprimer une adresse
	 * @param $id L'identifiant de l'adresse à supprimer
	 * @return void
	 */
	abstract public function delete($id);
	
	
	/**
	 * Méthode permettant d'enregistrer une adresse
	 * @param $address L'adresse à enregistrer
	 * @return void
	 */
	public function save(Carrousel $carrousel)
	{
		if($carrousel->isValid())
		{
			$carrousel->isNew() ? $this->add($carrousel) : $this->modify($carrousel);
		}
		else
		{
			throw new RuntimeException('L\'adresse doit être valide pour être enregistrée');
		}
	}
	
	/**
	 * Méthode permettant de modifier une adresse
	 * @param $address L'adresse à modifier
	 * @return void
	 */
	abstract protected function modify(Carrousel $carrousel);
	
	/**
	 * Méthode permettant de récupérer une liste d'adresses
	 * @param $userId Identifiant de l'utilisateur sur lequelle on veut récupérer des adresses
	 * @return array
	 */
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer une adresse spécifique
	 * @param $id Identifiant de l'adresse
	 * @return Address
	 */
	abstract public function get($id);
	
	abstract public function getByAnnounceId($announceId);
	
	abstract public function isAnnounceExist($announceId);
}

?>