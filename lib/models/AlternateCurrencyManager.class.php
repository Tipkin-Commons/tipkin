<?php
abstract class AlternateCurrencyManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter une monnaie alternative
	 * @param $alternateCurrency La monnaie alternative à ajouter
	 * @return void
	 */
	abstract protected function add(AlternateCurrency $alternateCurrency);
	
	/**
	 * Méthode permettant de supprimer une monnaie alternative
	 * @param $id L'identifiant de la monnaie alternative à supprimer
	 * @return void
	*/
	abstract public function delete($id);
	
	
	/**
	 * Méthode permettant d'enregistrer une monnaie alternative
	 * @param $address L'adresse à enregistrer
	 * @return void
	*/
	public function save(AlternateCurrency $alternateCurrency)
	{
		if($alternateCurrency->isValid())
		{
			$alternateCurrency->isNew() ? $this->add($alternateCurrency) : $this->modify($alternateCurrency);
		}
		else
		{
			throw new RuntimeException('La monnaie alternative doit être valide pour être enregistrée');
		}
	}
	
	/**
	 * Méthode permettant de modifier une monnaie alternative
	 * @param $alernateCurrency L'adresse à modifier
	 * @return void
	 */
	abstract protected function modify(AlternateCurrency $alernateCurrency);
	
	/**
	 * Méthode permettant de récupérer une liste de monnaie alternative
	 * @return array
	*/
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer une monnaie alternative spécifique
	 * @param $id Identifiant de la monnaie alternative
	 * @return AlternateCurrency
	*/
	abstract public function get($id);
}