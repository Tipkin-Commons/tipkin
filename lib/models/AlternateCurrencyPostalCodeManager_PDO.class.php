<?php

class AlternateCurrencyPostalCodeManager_PDO extends AlternateCurrencyPostalCodeManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'alternate_currency_postal_codes';

    /**
     * Méthode permettant d'ajouter une monnaie alternative
     * @param $alternateCurrencyPostalCode La monnaie alternative à ajouter
     * @return void
     */
    protected function add(AlternateCurrencyPostalCode $alternateCurrencyPostalCode){
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET POSTAL_CODE = :postalCode, ALTERNATE_CURRENCY_ID = :alternateCurrencyId');

        $q->bindValue(':postalCode', $alternateCurrencyPostalCode->getPostalCode());
        $q->bindValue(':alternateCurrencyId', $alternateCurrencyPostalCode->getAlternateCurrencyId());

        $q->execute();

        $alternateCurrencyPostalCode->setId($this->dao->lastInsertId());
    }

    /**
     * Méthode permettant de supprimer une monnaie alternative
     * @param $id L'identifiant de la monnaie alternative à supprimer
     * @return void
     */
    public function delete($id){
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }


    /**
     * Méthode permettant de modifier une monnaie alternative
     * @param $alernateCurrency L'adresse à modifier
     * @return void
     */
    protected function modify(AlternateCurrencyPostalCode $alernateCurrencyPostalCode){
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET POSTAL_CODE = :postalCode, ALTERNATE_CURRENCY_ID = :alternateCurrencyId WHERE ID = :id');

        $q->bindValue(':postalCode', $alternateCurrencyPostalCode->getPostalCode());
        $q->bindValue(':alternateCurrencyId', $alternateCurrencyPostalCode->getAlternateCurrencyId());

        $q->bindValue(':id', $alernateCurrencyPostalCode->id(), PDO::PARAM_INT);

        $q->execute();
    }

    /**
     * Méthode permettant de récupérer une liste de monnaie alternative
     * @return array
     */
    public function getListOf(){
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $alternateCurrencyPostalCodes = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $alternateCurrencyPostalCodes[] = new AlternateCurrencyPostalCode($data);
        }

        return $alternateCurrencyPostalCodes;
    }

    /**
     * Méthode permettant de récupérer une liste de monnaie alternative
     * @return array
     */
    public function getListByPostalCode($postalCode){
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE  `POSTAL_CODE`="' . $postalCode . '"');
        $q->execute();

        $alternateCurrencyPostalCodes = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $alternateCurrencyPostalCodes[] = new AlternateCurrencyPostalCode($data);
        }

        return $alternateCurrencyPostalCodes;
    }

    /**
     * Méthode permettant de récupérer une monnaie alternative spécifique
     * @param $id Identifiant de la monnaie alternative
     * @return AlternateCurrencyPostalCode
     */
    public function get($id){
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $alternateCurrencyPostalCode = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($alternateCurrencyPostalCode))
            return new AlternateCurrencyPostalCode($alternateCurrencyPostalCode);
        return null;
    }

}