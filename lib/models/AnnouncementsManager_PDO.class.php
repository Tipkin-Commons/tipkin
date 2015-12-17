<?php

class AnnouncementsManager_PDO extends AnnouncementsManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'announcements';

    /* (non-PHPdoc)
     * @see AnnouncementsManager::add()
     */
    protected function add(Announcement $announcement) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET TITLE = :title, DESCRIPTION = :description, PRICE_PUBLIC = :pricePublic, IS_FULL_DAY_PRICE = :isFullDayPrice, CAUTION = :caution, PHOTO_MAIN = :photoMain, PHOTO_OPTION_1 = :photoOption1, PHOTO_OPTION_2 = :photoOption2, TIPS = :tips, RAW_MATERIAL = :rawMaterial, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, REGION_ID =:regionId, DEPARTMENT_ID =:departmentId, IS_PUBLISHED = :isPublished, PUBLICATION_DATE = :publicationDate, END_PUBLICATION_DATE = :endPublicationDate, CATEGORY_ID = :categoryId, SUB_CATEGORY_ID = :subCategoryId, USER_ID = :userId, STATE_ID = :stateId, ADMIN_COMMENT = :adminComment, REF_ANNOUNCEMENT_ID = :refAnnouncementId');

        $q->bindValue(':title'              , $announcement->getTitle());
        $q->bindValue(':description'        , $announcement->getDescription());
        $q->bindValue(':pricePublic'        , $announcement->getPricePublic());
        $q->bindValue(':isFullDayPrice'     , $announcement->getIsFullDayPrice());
        $q->bindValue(':caution'            , $announcement->getCaution());
        $q->bindValue(':photoMain'          , $announcement->getPhotoMain());
        $q->bindValue(':photoOption1'       , $announcement->getPhotoOption1());
        $q->bindValue(':photoOption2'       , $announcement->getPhotoOption2());
        $q->bindValue(':tips'               , $announcement->getTips());
        $q->bindValue(':rawMaterial'        , $announcement->getRawMaterial());
        $q->bindValue(':address1'           , $announcement->getAddress1());
        $q->bindValue(':address2'           , $announcement->getAddress2());
        $q->bindValue(':zipCode'            , $announcement->getZipCode());
        $q->bindValue(':city'               , $announcement->getCity());
        $q->bindValue(':country'            , $announcement->getCountry());
        $q->bindValue(':regionId'           , $announcement->getRegionId());
        $q->bindValue(':departmentId'       , $announcement->getDepartmentId());
        $q->bindValue(':isPublished'        , $announcement->getIsPublished());
        $q->bindValue(':publicationDate'    , $announcement->getPublicationDate());
        $q->bindValue(':endPublicationDate' , $announcement->getEndPublicationDate());

        $q->bindValue(':categoryId'         , $announcement->getCategoryId(), PDO::PARAM_INT);
        $q->bindValue(':subCategoryId'      , $announcement->getSubCategoryId(), PDO::PARAM_INT);
        $q->bindValue(':userId'             , $announcement->getUserId(), PDO::PARAM_INT);
        $q->bindValue(':stateId'            , $announcement->getStateId(), PDO::PARAM_INT);

        $q->bindValue(':adminComment'       , $announcement->getAdminComment());
        $q->bindValue(':refAnnouncementId'  , $announcement->getRefAnnouncementId(), PDO::PARAM_INT);

        $q->execute();

        $announcement->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see AnnouncementsManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see AnnouncementsManager::deleteByUserId()
     */
    public function deleteByUserId($userId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_ID = '. (int) $userId);

    }

    /* (non-PHPdoc)
     * @see AnnouncementsManager::modify()
     */
    protected function modify(Announcement $announcement) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET TITLE = :title, DESCRIPTION = :description, PRICE_PUBLIC = :pricePublic, IS_FULL_DAY_PRICE = :isFullDayPrice, CAUTION = :caution, PHOTO_MAIN = :photoMain, PHOTO_OPTION_1 = :photoOption1, PHOTO_OPTION_2 = :photoOption2, TIPS = :tips, RAW_MATERIAL = :rawMaterial, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, REGION_ID =:regionId, DEPARTMENT_ID =:departmentId, IS_PUBLISHED = :isPublished, PUBLICATION_DATE = :publicationDate, END_PUBLICATION_DATE = :endPublicationDate, CATEGORY_ID = :categoryId, SUB_CATEGORY_ID = :subCategoryId, USER_ID = :userId, STATE_ID = :stateId, ADMIN_COMMENT = :adminComment, REF_ANNOUNCEMENT_ID = :refAnnouncementId WHERE ID = :id');

        $q->bindValue(':title'              , $announcement->getTitle());
        $q->bindValue(':description'        , $announcement->getDescription());
        $q->bindValue(':pricePublic'        , $announcement->getPricePublic());
        $q->bindValue(':isFullDayPrice'     , $announcement->getIsFullDayPrice());
        $q->bindValue(':caution'            , $announcement->getCaution());
        $q->bindValue(':photoMain'          , $announcement->getPhotoMain());
        $q->bindValue(':photoOption1'       , $announcement->getPhotoOption1());
        $q->bindValue(':photoOption2'       , $announcement->getPhotoOption2());
        $q->bindValue(':tips'               , $announcement->getTips());
        $q->bindValue(':rawMaterial'        , $announcement->getRawMaterial());
        $q->bindValue(':address1'           , $announcement->getAddress1());
        $q->bindValue(':address2'           , $announcement->getAddress2());
        $q->bindValue(':zipCode'            , $announcement->getZipCode());
        $q->bindValue(':city'               , $announcement->getCity());
        $q->bindValue(':country'            , $announcement->getCountry());
        $q->bindValue(':regionId'           , $announcement->getRegionId());
        $q->bindValue(':departmentId'       , $announcement->getDepartmentId());
        $q->bindValue(':isPublished'        , $announcement->getIsPublished());
        $q->bindValue(':publicationDate'    , $announcement->getPublicationDate());
        $q->bindValue(':endPublicationDate' , $announcement->getEndPublicationDate());

        $q->bindValue(':categoryId'         , $announcement->getCategoryId()        , PDO::PARAM_INT);
        $q->bindValue(':subCategoryId'      , $announcement->getSubCategoryId()     , PDO::PARAM_INT);
        $q->bindValue(':userId'             , $announcement->getUserId()            , PDO::PARAM_INT);
        $q->bindValue(':stateId'            , $announcement->getStateId()           , PDO::PARAM_INT);

        $q->bindValue(':adminComment'       , $announcement->getAdminComment());
        $q->bindValue(':refAnnouncementId'  , $announcement->getRefAnnouncementId() , PDO::PARAM_INT);

        $q->bindValue(':id'                 , $announcement->id());

        $q->execute();

    }

    /* (non-PHPdoc)
     * @see AnnouncementsManager::getListOf()
     */
    public function getListOf($userId = null) {
        $where = '';
        if(!is_null($userId))
            $where = 'WHERE USER_ID = ' . $userId;

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ' . $where .  ' ORDER BY TITLE');
        $q->execute();

        $announcements = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcements[] = new Announcement($data);
        }

        return $announcements;

    }

        public function getListOfLastPublished($limitMax = null) {
        $limit = '';
        if(!is_null($limitMax))
            $limit = 'LIMIT 0 , ' . $limitMax;

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE STATE_ID = 4 AND END_PUBLICATION_DATE > NOW() ORDER BY RAND() DESC ' . $limit);
        $q->execute();

        $announcements = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcements[] = new Announcement($data);
        }

        return $announcements;

    }

    /* (non-PHPdoc)
     * @see AnnouncementsManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $announcement = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($announcement))
            return new Announcement($announcement);
        return null;
    }

    public function getPendings()
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE STATE_ID = '. AnnouncementStates::STATE_PENDING );
        $q->execute();

       $announcements = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcements[] = new Announcement($data);
        }

        return $announcements;
    }

}

?>