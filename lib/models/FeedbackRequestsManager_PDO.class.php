<?php

class FeedbackRequestsManager_PDO extends FeedbackRequestsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'feedback_requests';

    /* (non-PHPdoc)
     * @see FeedbacksManager::add()
     */
    protected function add(FeedbackRequest $feedback) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET ANNOUNCE_ID = :announceId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, USER_AUTHOR_ID = :userAuthorId, RESERVATION_ID = :reservationId');

        $q->bindValue(':announceId'         , $feedback->getAnnounceId(), PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $feedback->getUserOwnerId(), PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $feedback->getUserSubscriberId(), PDO::PARAM_INT);
        $q->bindValue(':userAuthorId'       , $feedback->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':reservationId'      , $feedback->getReservationId(), PDO::PARAM_INT);

        $q->execute();

        $feedback->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::modify()
     */
    protected function modify(FeedbackRequest $feedback) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET ANNOUNCE_ID = :announceId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, USER_AUTHOR_ID = :userAuthorId, RESERVATION_ID = :reservationId WHERE ID = :id');

        $q->bindValue(':announceId'         , $feedback->getAnnounceId(), PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $feedback->getUserOwnerId(), PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $feedback->getUserSubscriberId(), PDO::PARAM_INT);
        $q->bindValue(':userAuthorId'       , $feedback->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':reservationId'      , $feedback->getReservationId(), PDO::PARAM_INT);

        $q->bindValue(':id'                 , $feedback->id(), PDO::PARAM_INT);

        $q->execute();

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByUserId()
     */
    public function getByUserId($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_AUTHOR_ID = :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $feedbackRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbackRequests[] = new FeedbackRequest($data);
        }

        return $feedbackRequests;

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByAnnounceId()
     */
    public function getByAnnounceId($announceId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCE_ID != :announceId');
        $q->bindValue(':announceId', (int) $announceId, PDO::PARAM_INT);
        $q->execute();

        $feedbackRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbackRequests[] = new FeedbackRequest($data);
        }

        return $feedbackRequests;
    }

    public function getByReservationId($reservationId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE RESERVATION_ID = :reservationId');
        $q->bindValue(':reservationId', (int) $reservationId, PDO::PARAM_INT);
        $q->execute();

        $feedbackRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbackRequests[] = new FeedbackRequest($data);
        }

        return $feedbackRequests;
    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByAuthorId()
     */
    public function getByAuthorId($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_AUTHOR_ID = :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $feedbackRequests = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbackRequests[] = new FeedbackRequest($data);
        }

        return $feedbackRequests;

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $feedbackRequest = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($feedbackRequest))
            return new FeedbackRequest($feedbackRequest);
        return null;
    }
}

?>