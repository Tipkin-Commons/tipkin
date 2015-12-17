<?php

class RegionsManager_PDO extends RegionsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'regions';

	/* (non-PHPdoc)
	 * @see RegionsManager::getListOf()
	 */
	public function getListOf() {
		$q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
		$q->execute();

		$regions = array();

		while ($data = $q->fetch(PDO::FETCH_ASSOC))
		{
			$regions[] = new Region($data);
		}

		return $regions;
	}

	public function get($id)
	{
		$q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $region = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($region))
        	return new Region($region);
        return null;
	}
}

?>