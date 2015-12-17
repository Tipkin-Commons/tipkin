<?php

class ContactsManager_PDO extends ContactsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'contacts';

    /* (non-PHPdoc)
     * @see ContactsManager::add()
     */
    protected function add(Contact $contact) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET USER_ID_1 = :userId1, USER_ID_2 = :userId2, CONTACT_GROUP_ID = :contactGroupId');

        $q->bindValue(':userId1'        , $contact->getUserId1()        , PDO::PARAM_INT);
        $q->bindValue(':userId2'        , $contact->getUserId2()        , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId' , $contact->getContactGroupId() , PDO::PARAM_INT);

        $q->execute();

        $contact->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see ContactsManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see ContactsManager::modify()
     */
    protected function modify(Contact $contact) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET USER_ID_1 = :userId1, USER_ID_2 = :userId2, CONTACT_GROUP_ID = :contactGroupId WHERE ID=:id');

        $q->bindValue(':userId1'        , $contact->getUserId1()        , PDO::PARAM_INT);
        $q->bindValue(':userId2'        , $contact->getUserId2()        , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId' , $contact->getContactGroupId() , PDO::PARAM_INT);

        $q->bindValue(':id'             , $contact->id()                    , PDO::PARAM_INT);

        $q->execute();

        $contact->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see ContactsManager::getListOfFrom()
     */
    public function getListOf($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID_1 = ' . (int)$userId . '  OR USER_ID_2 = ' . (int)$userId);
        $q->execute();

        $contacts = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $contacts[] = new Contact($data);
        }

        return $contacts;
    }
    /* (non-PHPdoc)
     * @see ContactsManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $contact = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contact))
            return new Contact($contact);
        return null;
    }

    public function getByCouple($userId1, $userId2)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (USER_ID_1 = :userId1 AND USER_ID_2 = :userId2) OR (USER_ID_1 = :userId2 AND USER_ID_2 = :userId1)');
        $q->bindValue(':userId1'        , $userId1  , PDO::PARAM_INT);
        $q->bindValue(':userId2'        , $userId2  , PDO::PARAM_INT);
        $q->execute();

        $contact = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contact))
            return new Contact($contact);
        return null;
    }

    public function isContactExistById($userId1, $userId2)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (USER_ID_1 = :userId1 AND USER_ID_2 = :userId2) OR (USER_ID_1 = :userId2 AND USER_ID_2 = :userId1)');
        $q->bindValue(':userId1'        , $userId1  , PDO::PARAM_INT);
        $q->bindValue(':userId2'        , $userId2  , PDO::PARAM_INT);
        $q->execute();

        $contact = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contact))
            return true;
        return false;

    }

    public function isContactExist(Contact $contact)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (USER_ID_1 = :userId1 AND USER_ID_2 = :userId2) OR (USER_ID_1 = :userId2 AND USER_ID_2 = :userId1)');
        $q->bindValue(':userId1'        , $contact->getUserId1()    , PDO::PARAM_INT);
        $q->bindValue(':userId2'        , $contact->getUserId2()    , PDO::PARAM_INT);
        $q->execute();

        $contact = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contact))
            return true;
        return false;
    }
}

?>