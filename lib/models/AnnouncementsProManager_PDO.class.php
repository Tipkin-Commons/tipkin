<?php

class AnnouncementsProManager_PDO extends AnnouncementsProManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'announcements_pro';

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::add()
     */
    protected function add(AnnouncementPro $announcementPro) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET TITLE = :title, DESCRIPTION = :description, PRICE_PUBLIC = :pricePublic, PHOTO_MAIN = :photoMain, PHOTO_OPTION_1 = :photoOption1, PHOTO_OPTION_2 = :photoOption2, TIPS = :tips, RAW_MATERIAL = :rawMaterial, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, REGION_ID =:regionId, DEPARTMENT_ID =:departmentId, IS_PUBLISHED = :isPublished, PUBLICATION_DATE = :publicationDate, CATEGORY_ID = :categoryId, SUB_CATEGORY_ID = :subCategoryId, USER_ID = :userId, STATE_ID = :stateId, ADMIN_COMMENT = :adminComment');

        $q->bindValue(':title'              , $announcementPro->getTitle());
        $q->bindValue(':description'        , $announcementPro->getDescription());
        $q->bindValue(':pricePublic'        , $announcementPro->getPricePublic());
        $q->bindValue(':photoMain'          , $announcementPro->getPhotoMain());
        $q->bindValue(':photoOption1'       , $announcementPro->getPhotoOption1());
        $q->bindValue(':photoOption2'       , $announcementPro->getPhotoOption2());
        $q->bindValue(':tips'               , $announcementPro->getTips());
        $q->bindValue(':rawMaterial'        , $announcementPro->getRawMaterial());
        $q->bindValue(':address1'           , $announcementPro->getAddress1());
        $q->bindValue(':address2'           , $announcementPro->getAddress2());
        $q->bindValue(':zipCode'            , $announcementPro->getZipCode());
        $q->bindValue(':city'               , $announcementPro->getCity());
        $q->bindValue(':country'            , $announcementPro->getCountry());
        $q->bindValue(':regionId'           , $announcementPro->getRegionId());
        $q->bindValue(':departmentId'       , $announcementPro->getDepartmentId());
        $q->bindValue(':isPublished'        , $announcementPro->getIsPublished());
        $q->bindValue(':publicationDate'    , $announcementPro->getPublicationDate());

        $q->bindValue(':categoryId'         , $announcementPro->getCategoryId()     , PDO::PARAM_INT);
        $q->bindValue(':subCategoryId'      , $announcementPro->getSubCategoryId()  , PDO::PARAM_INT);
        $q->bindValue(':userId'             , $announcementPro->getUserId()         , PDO::PARAM_INT);
        $q->bindValue(':stateId'            , $announcementPro->getStateId()        , PDO::PARAM_INT);

        $q->bindValue(':adminComment'       , $announcementPro->getAdminComment());

        $q->execute();

        $announcementPro->setId($this->dao->lastInsertId());
    }

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::delete()
     */
    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::deleteByUserId()
     */
    public function deleteByUserId($userId) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_ID = '. (int) $userId);

    }

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::modify()
     */
    protected function modify(AnnouncementPro $announcementPro) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET TITLE = :title, DESCRIPTION = :description, PRICE_PUBLIC = :pricePublic, PHOTO_MAIN = :photoMain, PHOTO_OPTION_1 = :photoOption1, PHOTO_OPTION_2 = :photoOption2, TIPS = :tips, RAW_MATERIAL = :rawMaterial, ADDRESS_1 = :address1, ADDRESS_2 = :address2, ZIP_CODE = :zipCode, CITY = :city, COUNTRY = :country, REGION_ID =:regionId, DEPARTMENT_ID =:departmentId, IS_PUBLISHED = :isPublished, PUBLICATION_DATE = :publicationDate, CATEGORY_ID = :categoryId, SUB_CATEGORY_ID = :subCategoryId, USER_ID = :userId, STATE_ID = :stateId, ADMIN_COMMENT = :adminComment WHERE ID = :id');

        $q->bindValue(':title'              , $announcementPro->getTitle());
        $q->bindValue(':description'        , $announcementPro->getDescription());
        $q->bindValue(':pricePublic'        , $announcementPro->getPricePublic());
        $q->bindValue(':photoMain'          , $announcementPro->getPhotoMain());
        $q->bindValue(':photoOption1'       , $announcementPro->getPhotoOption1());
        $q->bindValue(':photoOption2'       , $announcementPro->getPhotoOption2());
        $q->bindValue(':tips'               , $announcementPro->getTips());
        $q->bindValue(':rawMaterial'        , $announcementPro->getRawMaterial());
        $q->bindValue(':address1'           , $announcementPro->getAddress1());
        $q->bindValue(':address2'           , $announcementPro->getAddress2());
        $q->bindValue(':zipCode'            , $announcementPro->getZipCode());
        $q->bindValue(':city'               , $announcementPro->getCity());
        $q->bindValue(':country'            , $announcementPro->getCountry());
        $q->bindValue(':regionId'           , $announcementPro->getRegionId());
        $q->bindValue(':departmentId'       , $announcementPro->getDepartmentId());
        $q->bindValue(':isPublished'        , $announcementPro->getIsPublished());
        $q->bindValue(':publicationDate'    , $announcementPro->getPublicationDate());

        $q->bindValue(':categoryId'         , $announcementPro->getCategoryId()     , PDO::PARAM_INT);
        $q->bindValue(':subCategoryId'      , $announcementPro->getSubCategoryId()  , PDO::PARAM_INT);
        $q->bindValue(':userId'             , $announcementPro->getUserId()         , PDO::PARAM_INT);
        $q->bindValue(':stateId'            , $announcementPro->getStateId()        , PDO::PARAM_INT);

        $q->bindValue(':adminComment'       , $announcementPro->getAdminComment());

        $q->bindValue(':id'                 , $announcementPro->id());

        $q->execute();

    }

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::getListOf()
     */
    public function getListOf($userId = null) {
        $where = '';
        if(!is_null($userId))
            $where = 'WHERE USER_ID = ' . $userId;

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' ' . $where .  ' ORDER BY TITLE');
        $q->execute();

        $announcementsPro = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $announcementsPro[] = new AnnouncementPro($data);
        }

        return $announcementsPro;

    }

    /* (non-PHPdoc)
     * @see AnnouncementsProManager::get()
     */
    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $announcementPro = $q->fetch(PDO::FETCH_ASSOC);

        if (is_array($announcementPro))
            return new AnnouncementPro($announcementPro);
        return null;
    }

}

?>