<?php
abstract class AddressesManager extends Manager 
{
	/**
	 * Méthode permettant d'ajouter une addresse
	 * @param $address L'adresse à ajouter
	 * @return void
	 */
	abstract protected function add(Address $address);
	
	/**
	 * Méthode permettant de supprimer une adresse
	 * @param $id L'identifiant de l'adresse à supprimer
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
	 * Méthode permettant d'enregistrer une adresse
	 * @param $address L'adresse à enregistrer
	 * @return void
	 */
	public function save(Address $address)
	{
		if($address->isValid())
		{
			$address->isNew() ? $this->add($address) : $this->modify($address);
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
	abstract protected function modify(Address $address);
	
	/**
	 * Méthode permettant de récupérer une liste d'adresses
	 * @param $userId Identifiant de l'utilisateur sur lequelle on veut récupérer des adresses
	 * @return array
	 */
	abstract public function getListOf($userId);
	
	/**
	 * Méthode permettant de récupérer une adresse spécifique
	 * @param $id Identifiant de l'adresse
	 * @return Address
	 */
	abstract public function get($id);
}

?>