<?php

class CarrouselsManager_PDO extends CarrouselsManager {
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'carrousels';

    /* (non-PHPdoc)
     * @see CarrouselsManager::add()
     */
    protected function add(Carrousel $carrousel) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET ANNOUNCE_ID = :announceId');

        $q->bindValue(':announceId', $carrousel->getAnnounceId());

        $q->execute();

        $carrousel->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see CarrouselsManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see CarrouselsManager::modify()
     */
    protected function modify(Carrousel $carrousel) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET ANNOUNCE_ID = :announceId WHERE ID = :id');

        $q->bindValue(':announceId', $carrousel->getAnnounceId());
        $q->bindValue(':id', $carrousel->id());

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see CarrouselsManager::getListOf()
     */
    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ORDER BY RAND()');
        $q->execute();

        $carrousels = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $carrousels[] = new Carrousel($data);
        }

        return $carrousels;

    }

    /* (non-PHPdoc)
     * @see CarrouselsManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $carrousel = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($carrousel))
            return new Carrousel($carrousel);
        return null;

    }

    public function isAnnounceExist($announceId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCE_ID = :announceId');
        $q->bindValue(':announceId', (int) $announceId, PDO::PARAM_INT);
        $q->execute();

        $carrousel = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($carrousel))
            return true;
        return false;
    }

    public function getByAnnounceId($announceId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ANNOUNCE_ID = :announceId');
        $q->bindValue(':announceId', (int) $announceId, PDO::PARAM_INT);
        $q->execute();

        $carrousel = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($carrousel))
            return new Carrousel($carrousel);
        return null;
    }

}

?>