<?php

class CategoriesManager_PDO extends CategoriesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'categories';

    /* (non-PHPdoc)
     * @see CategoriesManager::add()
     */
    protected function add(Category $category) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET NAME = :name, DESCRIPTION = :description, PARENT_CATEGORY_ID = :parentCategoryId, IS_ROOT = :isRoot');

        $q->bindValue(':name', $category->getName());
        $q->bindValue(':description', $category->getDescription());
        $q->bindValue(':parentCategoryId', $category->getParentCategoryId());
        $q->bindValue(':isRoot', $category->getIsRoot());

        $q->execute();

        $category->setId($this->dao->lastInsertId());

    }

    /* (non-PHPdoc)
     * @see CategoriesManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function deleteByParentCategoryId($parentCategoryId)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE PARENT_CATEGORY_ID = '. (int) $parentCategoryId);
    }

    /* (non-PHPdoc)
     * @see CategoriesManager::modify()
     */
    protected function modify(Category $category) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET NAME = :name, DESCRIPTION = :description, PARENT_CATEGORY_ID = :parentCategoryId, IS_ROOT = :isRoot WHERE ID = :id');

        $q->bindValue(':name', $category->getName());
        $q->bindValue(':description', $category->getDescription());
        $q->bindValue(':parentCategoryId', $category->getParentCategoryId());
        $q->bindValue(':isRoot', $category->getIsRoot());

        $q->bindValue(':id', $category->id(), PDO::PARAM_INT);

        $q->execute();
    }

    /* (non-PHPdoc)
     * @see CategoriesManager::getListOf()
     */
    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ORDER BY NAME');
        $q->execute();

        $categories = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $categories[] = new Category($data);
        }

        return $categories;

    }

    /* (non-PHPdoc)
     * @see CategoriesManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $category = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($category))
            return new Category($category);
        return null;
    }

    public function hasAnnouncementsLinked($categoryId)
    {
        $query = 'SELECT ('.
        'SELECT COUNT(*) '.
        'FROM '.$this->table('announcements').' AS a'.
        'WHERE a.CATEGORY_ID = ' . $categoryId . ' OR a.SUB_CATEGORY_ID = ' . $categoryId .
        ') AS count1,'.
        '('.
        'SELECT COUNT(*) '.
        'FROM '.$this->table('announcements_pro').' as ap '.
        'WHERE ap.CATEGORY_ID = ' . $categoryId . ' OR ap.SUB_CATEGORY_ID = ' . $categoryId .
        ') AS count2 '.
        'FROM '.$this->table('dual').'';

        $q = $this->dao->prepare($query);
        $q->execute();

        $count = $q->fetch(PDO::FETCH_ASSOC);
        if($count['count1'] != 0 || $count['count2'] != 0)
            return true;
        return false;
    }

}

?>