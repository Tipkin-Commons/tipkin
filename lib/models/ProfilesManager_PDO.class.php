<?php

class ProfilesManager_PDO extends ProfilesManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'profiles';

    protected function add(Profile $profile) {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET GENDER = :gender, LASTNAME = :lastname, FIRSTNAME = :firstname, DESCRIPTION = :description, PHONE = :phone, MOBILE_PHONE = :mobilePhone, OFFICE_PHONE = :officePhone, WEBSITE = :website, AVATAR = :avatar, USER_ID = :userId, MAIN_ADDRESS_ID = :mainAddressId, ALTERNATE_CURRENCIES_USED = :alternateCurrenciesUsed');

        $q->bindValue(':gender', $profile->getGender());
        $q->bindValue(':lastname', $profile->getLastname());
        $q->bindValue(':firstname', $profile->getFirstname());
        $q->bindValue(':description', $profile->getDescription());
        $q->bindValue(':phone', $profile->getPhone());
        $q->bindValue(':mobilePhone', $profile->getMobilePhone());
        $q->bindValue(':officePhone', $profile->getOfficePhone());
        $q->bindValue(':website', $profile->getWebsite());
        $q->bindValue(':avatar', $profile->getAvatar());
        $q->bindValue(':userId', $profile->getUserId(), PDO::PARAM_INT);
        $q->bindValue(':mainAddressId', $profile->getMainAddressId(), PDO::PARAM_INT);
        $q->bindValue(':alternateCurrenciesUsed', $profile->getAlternateCurrenciesUsed());

        $q->execute();

        $profile->setId($this->dao->lastInsertId());
    }

    public function delete($id) {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function deleteByUserId($userId)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE USER_ID = '. (int) $userId);
    }

    protected function modify(Profile $profile) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET GENDER = :gender, LASTNAME = :lastname, FIRSTNAME = :firstname, DESCRIPTION = :description, PHONE = :phone, MOBILE_PHONE = :mobilePhone, OFFICE_PHONE = :officePhone, WEBSITE = :website, AVATAR = :avatar, USER_ID = :userId, MAIN_ADDRESS_ID = :mainAddressId, ALTERNATE_CURRENCIES_USED = :alternateCurrenciesUsed WHERE ID = :id');

        $q->bindValue(':gender', $profile->getGender());
        $q->bindValue(':lastname', $profile->getLastname());
        $q->bindValue(':firstname', $profile->getFirstname());
        $q->bindValue(':description', $profile->getDescription());
        $q->bindValue(':phone', $profile->getPhone());
        $q->bindValue(':mobilePhone', $profile->getMobilePhone());
        $q->bindValue(':officePhone', $profile->getOfficePhone());
        $q->bindValue(':website', $profile->getWebsite());
        $q->bindValue(':avatar', $profile->getAvatar());
        $q->bindValue(':userId', $profile->getUserId(), PDO::PARAM_INT);
        $q->bindValue(':mainAddressId', $profile->getMainAddressId(), PDO::PARAM_INT);
        $q->bindValue(':alternateCurrenciesUsed', $profile->getAlternateCurrenciesUsed());

        $q->bindValue(':id', $profile->id(), PDO::PARAM_INT);

        $q->execute();
    }

    public function getListOf() {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().'');
        $q->execute();

        $profiles = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $profiles[] = new Profile($data);
        }

        return $profiles;

    }

    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        return new Profile($q->fetch(PDO::FETCH_ASSOC));
    }

    public function getByUserId($userId)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE USER_ID = :userId');
        $q->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
        $q->execute();

        $profile = $q->fetch(PDO::FETCH_ASSOC);

        if(is_array($profile))
            return new Profile($profile);

        return null;
    }
}
?>