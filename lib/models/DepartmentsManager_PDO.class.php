<?php

class DepartmentsManager_PDO extends DepartmentsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'departments';

    /* (non-PHPdoc)
     * @see DepartmentsManager::getListOf()
     */
    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $departments = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $departments[] = new Department($data);
        }

        return $departments;

    }

    public function get($id)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', $id);
        $q->execute();

        $departments = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($departments))
            return new Department($departments);
        return null;
    }
}

?>