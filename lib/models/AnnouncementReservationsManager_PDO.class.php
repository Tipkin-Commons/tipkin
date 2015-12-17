<?php

class AnnouncementReservationsManager_PDO extends AnnouncementReservationsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'announcement_reservations';

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::add()
     */
    protected function add(AnnouncementReservation $announcementReservation) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET DATE = :date, DATE_END = :dateEnd, DATE_OPTION = :option, ANNOUNCEMENT_ID = :announcementId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, CONTACT_GROUP_ID = :contactGroupId, PRICE = :price, STATE_ID = :stateId, KEY_CHECK = :keyCheck, TRANSACTION_REF = :transactionRef');

        $q->bindValue(':date'               , $announcementReservation->getDate());
        $q->bindValue(':dateEnd'            , $announcementReservation->getDateEnd());
        $q->bindValue(':option'             , $announcementReservation->getDateOption());
        $q->bindValue(':announcementId'     , $announcementReservation->getAnnouncementId()     , PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $announcementReservation->getUserOwnerId()        , PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $announcementReservation->getUserSubscriberId()   , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId'     , $announcementReservation->getContactGroupId()     , PDO::PARAM_INT);
        $q->bindValue(':price'              , $announcementReservation->getPrice()              );
        $q->bindValue(':stateId'            , $announcementReservation->getStateId()            , PDO::PARAM_INT);
        $q->bindValue(':keyCheck'           , $announcementReservation->getKeyCheck()           );
        $q->bindValue(':transactionRef'     , $announcementReservation->getTransactionRef()     );

        $q->execute();

        $announcementReservation->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function deletePassedWaitingPaiement()
    {
        $today = new DateTime();
        $yesterday = new DateTime();
        //$intervalOneDays = new DateInterval('P1D');
        $intervalOneDays = new DateInterval('P15M');
        $yesterday->sub($intervalOneDays);

        $q = $this->dao->prepare('DELETE FROM '.$this->table().' WHERE (CREATED_TIME <= \'' . $yesterday->format('Y-m-d') . '\' AND STATE_ID = ' . PaiementStates::WAITING_PAIEMENT . ')');
        $q->execute();
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::deleteByAnnouncementId()
     */
    public function deleteByAnnouncementId($announcementId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = '. (int) $announcementId);
    }

    public function deleteByUserId($userId){
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_SUBSCRIBER_ID = '. (int) $userId . ' OR USER_OWNER_ID = ' . (int) $userId);
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::modify()
     */
    protected function modify(AnnouncementReservation $announcementReservation) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET DATE = :date, DATE_END = :dateEnd, DATE_OPTION = :option, ANNOUNCEMENT_ID = :announcementId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, CONTACT_GROUP_ID = :contactGroupId, PRICE = :price, STATE_ID = :stateId, KEY_CHECK = :keyCheck, TRANSACTION_REF = :transactionRef, UPDATED_TIME = FROM_UNIXTIME(:updatedTime), ADMIN_PROCEED = :adminProceed WHERE ID = :id');

        $q->bindValue(':date'               , $announcementReservation->getDate());
        $q->bindValue(':dateEnd'            , $announcementReservation->getDateEnd());
        $q->bindValue(':option'             , $announcementReservation->getDateOption());
        $q->bindValue(':announcementId'     , $announcementReservation->getAnnouncementId()     , PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $announcementReservation->getUserOwnerId()        , PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $announcementReservation->getUserSubscriberId()   , PDO::PARAM_INT);
        $q->bindValue(':contactGroupId'     , $announcementReservation->getContactGroupId()     , PDO::PARAM_INT);
        $q->bindValue(':price'              , $announcementReservation->getPrice()              );
        $q->bindValue(':stateId'            , $announcementReservation->getStateId()            , PDO::PARAM_INT);
        $q->bindValue(':keyCheck'           , $announcementReservation->getKeyCheck()           );
        $q->bindValue(':transactionRef'     , $announcementReservation->getTransactionRef()     );
        $q->bindValue(':updatedTime'        , $announcementReservation->getUpdatedTime()        );
        $q->bindValue(':adminProceed'       , $announcementReservation->getAdminProceed()       );

        $q->bindValue(':id'                 , $announcementReservation->id()                    , PDO::PARAM_INT);

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::getByAnnouncementId()
     */
    public function getByAnnouncementId($announcementId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = ' . $announcementId);
        $q->execute();

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return $announcementReservation;

    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $announcementReservation = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($announcementReservation))
            return new AnnouncementReservation($announcementReservation);
        return null;
    }

    public function getByUserId($userId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_OWNER_ID = ' . $userId . ' OR USER_SUBSCRIBER_ID = ' . $userId . ' ORDER BY DATE');
        $q->execute();

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return $announcementReservation;

    }

    public function getListOfPassed()
    {
        $today = new DateTime();
        $fiveDayBefore = new DateTime();
        $intervalFiveDays = new DateInterval('P5D');
        $fiveDayBefore->sub($intervalFiveDays);

//      echo 'SELECT * FROM '.$this->table().' WHERE (CREATED_TIME >= \'' . $fiveDayLater->format('Y-m-d') . '\' AND STATE_ID = ' . PaiementStates::WAITING_VALIDATION . ') OR ('.$this->table().'.DATE <= \'' . $today->format('Y-m-d') . '\'  AND STATE_ID = ' . PaiementStates::WAITING_VALIDATION . ')';
//      die;

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (CREATED_TIME <= \'' . $fiveDayBefore->format('Y-m-d') . '\' AND STATE_ID = ' . PaiementStates::WAITING_VALIDATION . ') OR ('.$this->table().'.DATE <= \'' . $today->format('Y-m-d') . '\'  AND STATE_ID = ' . PaiementStates::WAITING_VALIDATION . ')');
        //$q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ('.$this->table().'.DATE <= \'' . $today->format('Y-m-d') . '\'  AND STATE_ID = ' . PaiementStates::WAITING_VALIDATION . ')');
        $q->execute();

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return $announcementReservation;
    }

    public function isReservationExists(AnnouncementReservation $reservation)
    {
        //echo 'SELECT * FROM '.$this->table().' WHERE ('.$this->table().'.DATE >= \'' . $reservation->getDate() . '\' AND DATE_END <= \'' . $reservation->getDateEnd() . '\' AND STATE_ID != ' . PaiementStates::CANCELED . ')';
        //die;
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ('.$this->table().'.DATE >= \'' . $reservation->getDate() . '\' AND DATE_END <= \'' . $reservation->getDateEnd() . '\' AND STATE_ID != ' . PaiementStates::CANCELED . ' AND ANNOUNCEMENT_ID = '.  $reservation->getAnnouncementId()  .')');
        $q->execute();

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return count($announcementReservation);
    }

    public function getListOfPassedValidated()
    {
        $today = new DateTime();
        $yesterday = new DateTime();
        $intervalOneDays = new DateInterval('P1D');
        $yesterday->sub($intervalOneDays);

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (DATE_END = \'' . $yesterday->format('Y-m-d') . '\' AND STATE_ID = ' . PaiementStates::VALIDATED . ')');
        $q->execute();

        //echo 'SELECT * FROM '.$this->table().' WHERE (DATE_END = \'' . $yesterday->format('Y-m-d') . '\' AND STATE_ID = ' . PaiementStates::VALIDATED . ') <br />';

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return $announcementReservation;
    }

    public function getListOf()
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $announcementReservation = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementReservation[] = new AnnouncementReservation($data);
        }

        return $announcementReservation;
    }
}

?>