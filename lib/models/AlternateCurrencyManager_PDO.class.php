<?php

class AlternateCurrencyManager_PDO extends AlternateCurrencyManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'alternate_currency';

    /**
     * Méthode permettant d'ajouter une monnaie alternative
     * @param $alternateCurrency La monnaie alternative à ajouter
     * @return void
     */
    protected function add(AlternateCurrency $alternateCurrency){
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET NAME = :name, IMAGE_URL = :imageUrl, RATE = :rate');

        $q->bindValue(':name', $alternateCurrency->getName());
        $q->bindValue(':imageUrl', $alternateCurrency->getImageUrl());
        $q->bindValue(':rate', $alternateCurrency->getRate());

        $q->execute();

        $alternateCurrency->setId($this->dao->lastInsertId());
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
    protected function modify(AlternateCurrency $alernateCurrency){
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET NAME = :name, IMAGE_URL = :imageUrl, RATE = :rate WHERE ID = :id');

        $q->bindValue(':name', $alternateCurrency->getName());
        $q->bindValue(':imageUrl', $alternateCurrency->getImageUrl());
        $q->bindValue(':rate', $alternateCurrency->getRate());

        $q->bindValue(':id', $alernateCurrency->id(), PDO::PARAM_INT);

        $q->execute();
    }

    /**
     * Méthode permettant de récupérer une liste de monnaie alternative
     * @return array
    */
    public function getListOf(){
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ORDER BY TITLE');
        $q->execute();

        $alternateCurrencies = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $alternateCurrencies[] = new AlternateCurrency($data);
        }

        return $alternateCurrencies;
    }

    /**
     * Méthode permettant de récupérer une monnaie alternative spécifique
     * @param $id Identifiant de la monnaie alternative
     * @return AlternateCurrency
    */
    public function get($id){
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $alternateCurrency = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($alternateCurrency))
            return new AlternateCurrency($alternateCurrency);
        return null;
    }

}