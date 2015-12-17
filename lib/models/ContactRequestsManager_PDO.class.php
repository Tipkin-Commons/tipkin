<?php

class ContactRequestsManager_PDO extends ContactRequestsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'contact_requests';

    /* (non-PHPdoc)
     * @see ContactRequestsManager::add()
     */
    protected function add(ContactRequest $contactRequest) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET USER_ID_TO = :userIdTo, USER_ID_FROM = :userIdFrom, CONTACT_GROUP_ID = :contactGroupId');

        $q->bindValue(':userIdTo'       , $contactRequest->getUserIdTo()        , PDO::PARAM_INT);
        $q->bindValue(':userIdFrom'     , $contactRequest->getUserIdFrom()      , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId' , $contactRequest->getContactGroupId()  , PDO::PARAM_INT);

        $q->execute();

        $contactRequest->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see ContactRequestsManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see ContactRequestsManager::modify()
     */
    protected function modify(ContactRequest $contactRequest) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET USER_ID_TO = :userIdTo, USER_ID_FROM = :userIdFrom, CONTACT_GROUP_ID = :contactGroupId WHERE ID=:id');

        $q->bindValue(':userIdTo'       , $contactRequest->getUserIdTo()        , PDO::PARAM_INT);
        $q->bindValue(':userIdFrom'     , $contactRequest->getUserIdFrom()      , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId' , $contactRequest->getContactGroupId()  , PDO::PARAM_INT);

        $q->bindValue(':id'             , $contactRequest->id()                 , PDO::PARAM_INT);

        $q->execute();

        $contactRequest->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see ContactRequestsManager::getListOfFrom()
     */
    public function getListOfFrom($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID_FROM = ' . $userId);
        $q->execute();

        $contactRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $contactRequests[] = new ContactRequest($data);
        }

        return $contactRequests;
    }

    /* (non-PHPdoc)
     * @see ContactRequestsManager::getListOfTo()
     */
    public function getListOfTo($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID_TO = ' . $userId);
        $q->execute();

        $contactRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $contactRequests[] = new ContactRequest($data);
        }

        return $contactRequests;
    }

    /* (non-PHPdoc)
     * @see ContactRequestsManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $contactRequest = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contactRequest))
            return new ContactRequest($contactRequest);
        return null;
    }

    public function isContactRequestExist(ContactRequest $contactRequest)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (USER_ID_TO = :userIdTo AND USER_ID_FROM = :userIdFrom) OR (USER_ID_TO = :userIdFrom AND USER_ID_FROM = :userIdTo)');
        $q->bindValue(':userIdTo'       , $contactRequest->getUserIdTo()    , PDO::PARAM_INT);
        $q->bindValue(':userIdFrom'     , $contactRequest->getUserIdFrom()  , PDO::PARAM_INT);
        $q->execute();

        $contactRequest = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($contactRequest))
            return true;
        return false;
    }

}

?>