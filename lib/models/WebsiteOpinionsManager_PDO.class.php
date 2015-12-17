<?php

class WebsiteOpinionsManager_PDO extends WebsiteOpinionsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'website_opinions';

    /* (non-PHPdoc)
     * @see WebsiteOpinionsManager::add()
     */
    protected function add(WebsiteOpinion $websiteOpinion) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET USERNAME = :username, COMMENT = :comment');

        $q->bindValue(':username', $websiteOpinion->getUsername());
        $q->bindValue(':comment', $websiteOpinion->getComment());

        $q->execute();

        $websiteOpinion->setId($this->dao->lastInsertId());

    }

    /* (non-PHPdoc)
     * @see WebsiteOpinionsManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);

    }

    /* (non-PHPdoc)
     * @see WebsiteOpinionsManager::modify()
     */
    protected function modify(WebsiteOpinion $websiteOpinion) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET USERNAME = :username, COMMENT = :comment, IS_PUBLISHED = :isPublished WHERE ID = :id');

        $q->bindValue(':username', $websiteOpinion->getUsername());
        $q->bindValue(':comment', $websiteOpinion->getComment());
        $q->bindValue(':isPublished', $websiteOpinion->getIsPublished());

        $q->bindValue(':id', $websiteOpinion->id());

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see WebsiteOpinionsManager::getListOf()
     */
    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ORDER BY CREATION_DATE DESC');
        $q->execute();

        $websiteOpinions = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $websiteOpinions[] = new WebsiteOpinion($data);
        }

        return $websiteOpinions;

    }

    /* (non-PHPdoc)
     * @see WebsiteOpinionsManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $websiteOpinions = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($websiteOpinions))
            return new WebsiteOpinion($websiteOpinions);
        return null;

    }


}

?>