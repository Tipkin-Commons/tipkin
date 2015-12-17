<?php

class ProfilesProManager_PDO extends ProfilesProManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'profiles_pro';

    protected function add(ProfilePro $profilePro) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET COMPANY_NAME = :companyName, LASTNAME = :lastname, FIRSTNAME = :firstname, DESCRIPTION = :description, PHONE = :phone, MOBILE_PHONE = :mobilePhone, OFFICE_PHONE = :officePhone, WEBSITE = :website, AVATAR = :avatar, USER_ID = :userId, MAIN_ADDRESS_ID = :mainAddressId');

        $q->bindValue(':companyName', $profilePro->getCompanyName());
        $q->bindValue(':lastname', $profilePro->getLastname());
        $q->bindValue(':firstname', $profilePro->getFirstname());
        $q->bindValue(':description', $profilePro->getDescription());
        $q->bindValue(':phone', $profilePro->getPhone());
        $q->bindValue(':mobilePhone', $profilePro->getMobilePhone());
        $q->bindValue(':officePhone', $profilePro->getOfficePhone());
        $q->bindValue(':website', $profilePro->getWebsite());
        $q->bindValue(':avatar', $profilePro->getAvatar());
        $q->bindValue(':userId', $profilePro->getUserId(), PDO::PARAM_INT);
        $q->bindValue(':mainAddressId', $profilePro->getMainAddressId(), PDO::PARAM_INT);

        $q->execute();

        $profilePro->setId($this->dao->lastInsertId());
    }

    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function deleteByUserId($userId)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_ID = '. (int) $userId);
    }

    protected function modify(ProfilePro $profilePro) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET COMPANY_NAME = :companyName, LASTNAME = :lastname, FIRSTNAME = :firstname,  DESCRIPTION = :description, PHONE = :phone, MOBILE_PHONE = :mobilePhone, OFFICE_PHONE = :officePhone, WEBSITE = :website, AVATAR = :avatar, USER_ID = :userId, MAIN_ADDRESS_ID = :mainAddressId WHERE ID = :id');

        $q->bindValue(':companyName'    , $profilePro->getCompanyName());
        $q->bindValue(':lastname'       , $profilePro->getLastname());
        $q->bindValue(':firstname'      , $profilePro->getFirstname());
        $q->bindValue(':description'    , $profilePro->getDescription());
        $q->bindValue(':phone'          , $profilePro->getPhone());
        $q->bindValue(':mobilePhone'    , $profilePro->getMobilePhone());
        $q->bindValue(':officePhone'    , $profilePro->getOfficePhone());
        $q->bindValue(':website'        , $profilePro->getWebsite());
        $q->bindValue(':avatar'         , $profilePro->getAvatar());
        $q->bindValue(':userId'         , $profilePro->getUserId(), PDO::PARAM_INT);
        $q->bindValue(':mainAddressId'  , $profilePro->getMainAddressId(), PDO::PARAM_INT);

        $q->bindValue(':id'             , $profilePro->id(), PDO::PARAM_INT);

        $q->execute();
    }

    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $profiles = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $profiles[] = new ProfilePro($data);
        }

        return $profiles;

    }

    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        return new ProfilePro($q->fetch(PDO::FETCH_ASSOC));
    }

    public function getByUserId($userId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID = :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $profile = $q->fetch(PDO::FETCH_ASSOC);

        if(is_array($profile))
            return new ProfilePro($profile);

        return null;
    }
}
?>