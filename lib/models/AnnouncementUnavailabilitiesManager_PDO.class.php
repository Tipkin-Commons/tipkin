<?php

class AnnouncementUnavailabilitiesManager_PDO extends AnnouncementUnavailabilitiesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'announcement_unavailabilities';

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::add()
     */
    protected function add(AnnouncementUnavailability $announcementUnavailability) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET DATE = :date, DATE_OPTION = :option, ANNOUNCEMENT_ID = :announcementId');

        $q->bindValue(':date', $announcementUnavailability->getDate());
        $q->bindValue(':option', $announcementUnavailability->getDateOption());
        $q->bindValue(':announcementId', $announcementUnavailability->getAnnouncementId(), PDO::PARAM_INT);

        $q->execute();

        $announcementUnavailability->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::deleteByAnnouncementId()
     */
    public function deleteByAnnouncementId($announcementId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = '. (int) $announcementId);
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::modify()
     */
    protected function modify(AnnouncementUnavailability $announcementUnavailability) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET DATE = :date, DATE_OPTION = :option, ANNOUNCEMENT_ID = :announcementId WHERE ID = :id');

        $q->bindValue(':date', $announcementUnavailability->getDate());
        $q->bindValue(':option', $announcementUnavailability->getOption());
        $q->bindValue(':announcementId', $announcementUnavailability->getAnnouncementId(), PDO::PARAM_INT);

        $q->bindValue(':id', $announcementUnavailability->id(), PDO::PARAM_INT);

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::getByAnnouncementId()
     */
    public function getByAnnouncementId($announcementId) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCEMENT_ID = ' . $announcementId);
        $q->execute();

        $announcementUnavailabilities = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementUnavailabilities[] = new AnnouncementUnavailability($data);
        }

        return $announcementUnavailabilities;

    }

    /* (non-PHPdoc)
     * @see AnnouncementUnavailabilitiesManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $announcementUnavailability = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($announcementUnavailability))
            return new AnnouncementUnavailability($announcementUnavailability);
        return null;
    }


}

?>