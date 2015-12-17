<?php

class FeedbacksManager_PDO extends FeedbacksManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'feedbacks';

    /* (non-PHPdoc)
     * @see FeedbacksManager::add()
     */
    protected function add(Feedback $feedback) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET ANNOUNCE_ID = :announceId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, USER_AUTHOR_ID = :userAuthorId, RESERVATION_ID = :reservationId, MARK = :mark, COMMENT = :comment');

        $q->bindValue(':announceId'         , $feedback->getAnnounceId(), PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $feedback->getUserOwnerId(), PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $feedback->getUserSubscriberId(), PDO::PARAM_INT);
        $q->bindValue(':userAuthorId'       , $feedback->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':reservationId'      , $feedback->getReservationId(), PDO::PARAM_INT);
        $q->bindValue(':mark'               , $feedback->getMark());
        $q->bindValue(':comment'            , $feedback->getComment());

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
    protected function modify(Feedback $feedback) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET ANNOUNCE_ID = :announceId, USER_OWNER_ID = :userOwnerId, USER_SUBSCRIBER_ID = :userSubscriberId, USER_AUTHOR_ID = :userAuthorId, RESERVATION_ID = :reservationId, MARK = :mark, COMMENT = :comment WHERE ID = :id');

        $q->bindValue(':announceId'         , $feedback->getAnnounceId(), PDO::PARAM_INT);
        $q->bindValue(':userOwnerId'        , $feedback->getUserOwnerId(), PDO::PARAM_INT);
        $q->bindValue(':userSubscriberId'   , $feedback->getUserSubscriberId(), PDO::PARAM_INT);
        $q->bindValue(':userAuthorId'       , $feedback->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':reservationId'      , $feedback->getReservationId(), PDO::PARAM_INT);
        $q->bindValue(':mark'               , $feedback->getMark());
        $q->bindValue(':comment'            , $feedback->getComment());

        $q->bindValue(':id'                 , $feedback->id(), PDO::PARAM_INT);

        $q->execute();

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByUserId()
     */
    public function getByUserId($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (USER_OWNER_ID = :userId OR USER_SUBSCRIBER_ID = :userId) AND USER_AUTHOR_ID != :userId ORDER BY CREATION_DATE DESC');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $feedbacks = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbacks[] = new Feedback($data);
        }

        return $feedbacks;

    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByAnnounceId()
     */
    public function getByAnnounceId($announceId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCE_ID = :announceId AND USER_AUTHOR_ID != USER_OWNER_ID ORDER BY CREATION_DATE DESC');
        $q->bindValue(':announceId', (int) $announceId, PDO::PARAM_INT);
        $q->execute();

        $feedbacks = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbacks[] = new Feedback($data);
        }

        return $feedbacks;
    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::getByAuthorId()
     */
    public function getByAuthorId($userId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_AUTHOR_ID = :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $feedbacks = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbacks[] = new Feedback($data);
        }

        return $feedbacks;

    }

    public function getByReservationId($reservationId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE RESERVATION_ID = :reservationId');
        $q->bindValue(':reservationId', (int) $reservationId, PDO::PARAM_INT);
        $q->execute();

        $feedbacks = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $feedbacks[] = new Feedback($data);
        }

        return $feedbacks;

    }

    public function getMarkByUserId($userId)
    {
        $q = $this->dao->prepare('SELECT AVG(MARK) FROM '.$this->table().' WHERE (USER_OWNER_ID = :userId OR USER_SUBSCRIBER_ID = :userId) AND USER_AUTHOR_ID != :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $feedbackMark = $q->fetch(PDO::FETCH_ASSOC);

        return round($feedbackMark['AVG(MARK)']);
    }

    /* (non-PHPdoc)
     * @see FeedbacksManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $feedback = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($feedback))
            return new Feedback($feedback);
        return null;
    }
}

?>