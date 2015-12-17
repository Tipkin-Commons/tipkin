<?php
abstract class AlternateCurrencyPostalCodeManager extends Manager
{
	/**
	 * Méthode permettant d'ajouter une monnaie alternative
	 * @param $alternateCurrencyPostalCode La monnaie alternative à ajouter
	 * @return void
	 */
	abstract protected function add(AlternateCurrencyPostalCode $alternateCurrencyPostalCode);

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
	public function save(AlternateCurrencyPostalCode $alternateCurrencyPostalCode)
	{
		if($alternateCurrencyPostalCode->isValid())
		{
			$alternateCurrencyPostalCode->isNew() ? $this->add($alternateCurrencyPostalCode) : $this->modify($alternateCurrencyPostalCode);
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
	abstract protected function modify(AlternateCurrencyPostalCode $alernateCurrency);

	/**
	 * Méthode permettant de récupérer une liste de monnaie alternative
	 * @return array
	*/
	abstract public function getListOf();
	
	/**
	 * Méthode permettant de récupérer une liste de monnaie alternative
	 * @return array
	 */
	abstract public function getListByPostalCode($postalCode);

	/**
	 * Méthode permettant de récupérer une monnaie alternative spécifique
	 * @param $id Identifiant de la monnaie alternative
	 * @return AlternateCurrencyPostalCode
	*/
	abstract public function get($id);
}