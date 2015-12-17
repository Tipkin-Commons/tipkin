<?php

class ModeratesManager_PDO extends ModeratesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'moderates';

    /* (non-PHPdoc)
     * @see ModeratesManager::add()
     */
    protected function add(Moderate $moderate) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET USER_AUTHOR_ID = :userAuthorId, TYPE = :type, TYPE_ID = :typeId, MESSAGE = :message');

        $q->bindValue(':userAuthorId',  $moderate->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':type',          $moderate->getType()        , PDO::PARAM_INT);
        $q->bindValue(':typeId',        $moderate->getTypeId()      , PDO::PARAM_INT);
        $q->bindValue(':message',       $moderate->getMessage());

        $q->execute();

        $moderate->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see ModeratesManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see ModeratesManager::deleteByFeedbackId()
     */
    public function deleteByFeedbackId($feedbackId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE TYPE_ID = '. (int) $feedbackId . ' AND TYPE = ' . Moderate::TYPE_FEEDBACK);

    }

    /* (non-PHPdoc)
     * @see ModeratesManager::modify()
     */
    protected function modify(Moderate $moderate) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET USER_AUTHOR_ID = :userAuthorId, TYPE = :type, TYPE_ID = :typeId, MESSAGE = :message WHERE ID = :id');

        $q->bindValue(':userAuthorId',  $moderate->getUserAuthorId(), PDO::PARAM_INT);
        $q->bindValue(':type',          $moderate->getType()        , PDO::PARAM_INT);
        $q->bindValue(':typeId',        $moderate->getTypeId()      , PDO::PARAM_INT);
        $q->bindValue(':message',       $moderate->getMessage());

        $q->bindValue(':id',            $moderate->id()     , PDO::PARAM_INT);

        $q->execute();

        $moderate->setId($this->dao->lastInsertId());

    }

    /* (non-PHPdoc)
     * @see ModeratesManager::getListOf()
     */
    public function getListOf($type) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE TYPE = ' . $type);
        $q->execute();

        $moderates = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $moderates[] = new Moderate($data);
        }

        return $moderates;

    }

    /* (non-PHPdoc)
     * @see ModeratesManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $moderate = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($moderate))
            return new Moderate($moderate);
        return null;
    }
}

?>