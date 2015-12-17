<?php
class RolesManager_PDO extends RolesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'user_roles';


    public function getListOf()
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $roles = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $roles[] = new Role($data);
        }

        return $roles;
    }

    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        return new Role($q->fetch(PDO::FETCH_ASSOC));
    }


}

?>