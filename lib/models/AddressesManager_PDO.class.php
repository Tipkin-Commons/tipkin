<?php

class AddressesManager_PDO extends AddressesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'addresses';

    protected function add(Address $address)
    {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET TITLE = :title, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, USER_ID = :userId');
        $q->bindValue(':title', $address->getTitle());
        $q->bindValue(':address1', $address->getAddress1());
        $q->bindValue(':address2', $address->getAddress2());
        $q->bindValue(':zipCode', $address->getZipCode());
        $q->bindValue(':city', $address->getCity());
        $q->bindValue(':country', $address->getCountry());
        $q->bindValue(':userId', $address->getUserId(), PDO::PARAM_INT);

        $q->execute();

        $address->setId($this->dao->lastInsertId());
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function deleteByUserId($userId)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_ID = '. (int) $userId);
    }

    protected function modify(Address $address) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET TITLE = :title, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, USER_ID = :userId WHERE ID = :id');

        $q->bindValue(':title', $address->getTitle());
        $q->bindValue(':address1', $address->getAddress1());
        $q->bindValue(':address2', $address->getAddress2());
        $q->bindValue(':zipCode', $address->getZipCode());
        $q->bindValue(':city', $address->getCity());
        $q->bindValue(':country', $address->getCountry());
        $q->bindValue(':userId', $address->getUserId(), PDO::PARAM_INT);

        $q->bindValue(':id', $address->id(), PDO::PARAM_INT);

        $q->execute();
    }

    public function getListOf($userId) {
        if(!is_int($userId))
            throw new InvalidArgumentException('L\'identifiant de l\'utilisateur doit être un entier.');

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID = ' . $userId . ' ORDER BY TITLE');
        $q->execute();

        $adresses = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $adresses[] = new Address($data);
        }

        return $adresses;

    }

    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $address = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($address))
            return new Address($address);
        return null;
    }
}

?>