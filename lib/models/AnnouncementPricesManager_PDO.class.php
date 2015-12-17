<?php

class AnnouncementPricesManager_PDO extends AnnouncementPricesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'announcement_prices';

    /* (non-PHPdoc)
     * @see AnnouncementPricesManager::add()
     */
    protected function add(AnnouncementPrice $announcementPrice) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET HALF_DAY = :halfDay, DAY = :day, WEEK_END = :weekEnd, WEEK = :week, FORTNIGHT = :fortnight, IS_ACTIVE = :isActive, ANNOUNCEMENT_ID = :announcementId, CONTACT_GROUP_ID = :contactGroupId');

        $q->bindValue(':halfDay'            , $announcementPrice->getHalfDay());
        $q->bindValue(':day'                , $announcementPrice->getDay());
        $q->bindValue(':weekEnd'            , $announcementPrice->getWeekEnd());
        $q->bindValue(':week'               , $announcementPrice->getWeek());
        $q->bindValue(':fortnight'          , $announcementPrice->getFortnight());
        $q->bindValue(':isActive'           , $announcementPrice->getIsActive());

        $q->bindValue(':announcementId'     , $announcementPrice->getAnnouncementId(), PDO::PARAM_INT);
        $q->bindValue(':contactGroupId'     , $announcementPrice->getContactGroupId(), PDO::PARAM_INT);

        $q->execute();

        $announcementPrice->setId($this->dao->lastInsertId());

    }

    /* (non-PHPdoc)
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see AnnouncementPricesManager::deleteByAnnouncementId()
     */
    public function deleteByAnnouncementId($announcementId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = '. (int) $announcementId);
    }

    /* (non-PHPdoc)
     * @see AnnouncementPricesManager::modify()
     */
    protected function modify(AnnouncementPrice $announcementPrice) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET HALF_DAY = :halfDay, DAY = :day, WEEK_END = :weekEnd, WEEK = :week, FORTNIGHT = :fortnight, IS_ACTIVE = :isActive, ANNOUNCEMENT_ID = :announcementId, CONTACT_GROUP_ID = :contactGroupId WHERE ID = :id');

        $q->bindValue(':halfDay'            , $announcementPrice->getHalfDay());
        $q->bindValue(':day'                , $announcementPrice->getDay());
        $q->bindValue(':weekEnd'            , $announcementPrice->getWeekEnd());
        $q->bindValue(':week'               , $announcementPrice->getWeek());
        $q->bindValue(':fortnight'          , $announcementPrice->getFortnight());
        $q->bindValue(':isActive'           , $announcementPrice->getIsActive());

        $q->bindValue(':announcementId'     , $announcementPrice->getAnnouncementId(), PDO::PARAM_INT);
        $q->bindValue(':contactGroupId'     , $announcementPrice->getContactGroupId(), PDO::PARAM_INT);

        $q->bindValue(':id'                 , $announcementPrice->id(), PDO::PARAM_INT);

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see AnnouncementPricesManager::getListOf()
     */
    public function getByAnnouncementId($announcementId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = :announcementId');
        $q->bindValue(':announcementId', (int) $announcementId, PDO::PARAM_INT);
        $q->execute();

        $announcementPrices = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementPrices[] = new AnnouncementPrice($data);
        }

        return $announcementPrices;
    }

    /* (non-PHPdoc)
     * @see AnnouncementPricesManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $announcementPrice = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($announcementPrice))
            return new Announcement($announcementPrice);
        return null;
    }
}

?>