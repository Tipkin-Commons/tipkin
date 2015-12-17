<?php

class UsersManager_PDO extends UsersManager
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'users';

    public function add(Users $user)
    {
        $q = $this->dao->prepare('INSERT INTO '.$this->table().' SET USERNAME = :username, PASSWORD = :password, MAILINGSTATE = :mailingstate, MAIL = :mail, ROLE_ID = :role_id, IS_ACTIVE = :is_active, IS_MAIL_VERIFIED = :is_mail_verified, ACTIVATION_KEY = :activation_key');

        $q->bindValue(':username', $user->getUsername());
        $q->bindValue(':password', $user->getPassword());
        $q->bindValue(':mail', $user->getMail());
        $q->bindValue(':is_active', $user->getIsActive());
        $q->bindValue(':role_id', $user->getRoleId(), PDO::PARAM_INT);
        $q->bindValue(':is_mail_verified', $user->getIsMailVerified());
        $q->bindValue(':activation_key', $user->getActivationKey());
        $q->bindvalue(':mailingstate', $user->getMailingState());

        $q->execute();

        $user->setId($this->dao->lastInsertId());

    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM '.$this->table().' WHERE ID = '. (int) $id);
    }

    public function modify(Users $user) {
        $q = $this->dao->prepare('UPDATE '.$this->table().' SET USERNAME = :username, PASSWORD = :password, MAILINGSTATE = :mailingstate, MAIL = :mail, ROLE_ID = :role_id, IS_ACTIVE = :is_active, IS_MAIL_VERIFIED = :is_mail_verified, ACTIVATION_KEY = :activation_key WHERE ID = :id');

        $q->bindValue(':username', $user->getUsername());
        $q->bindValue(':password', $user->getPassword());
        $q->bindValue(':mail', $user->getMail());
        $q->bindValue(':is_active', $user->getIsActive());
        $q->bindValue(':role_id', $user->getRoleId(), PDO::PARAM_INT);
        $q->bindValue(':is_mail_verified', $user->getIsMailVerified());
        $q->bindValue(':activation_key', $user->getActivationKey());
        $q->bindvalue(':mailingstate', $user->getMailingState());

        $q->bindValue(':id', $user->id(), PDO::PARAM_INT);

        $q->execute();

    }

    public function getListOf($roleId = '%')
    {
        if(!is_int($roleId) && $roleId != '%')
            throw new InvalidArgumentException('L\'identifiant du rôle doit être un entier ou laisser la méthode sans paramètre.');

        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ROLE_ID LIKE \'' . $roleId . '\'');
        $q->execute();

        $users = array();

        while ($data = $q->fetch(PDO::FETCH_ASSOC))
        {
            $users[] = new Users($data);
        }


        return $users;
    }

    public function get($id) {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE ID = :id');
        $q->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $q->execute();

        $user = $q->fetch(PDO::FETCH_ASSOC);
        //var_dump($user);
        return (is_array($user) ? new Users($user) : null);
    }

    /**
     * (non-PHPdoc)
     * @see UsersManager::authenticate()
     */
    public function authenticate($login, $password)
    {
    	$crypt_password = Users::cryptPassword($password, Tipkin\Config::get('secret-key'));
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE (LOWER(MAIL) = :login OR LOWER(USERNAME) = :login) AND PASSWORD = :password');
        $q->bindValue(':login', $login);
        $q->bindValue(':password', $crypt_password);
        $q->execute();

        $user = $q->fetch(PDO::FETCH_ASSOC);

        return (is_array($user) ? new Users($user) : null);

    }

    public function isUsernameOrMailExist($username, $mail)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE LOWER(USERNAME) = :username OR LOWER(MAIL) = :mail');
        $q->bindValue(':username', $username);
        $q->bindValue(':mail', $mail);
        $q->execute();

        $user = $q->fetch(PDO::FETCH_ASSOC);
        return (is_array($user));
    }

    public function getByMail($mail)
    {
        $q = $this->dao->prepare('SELECT * FROM '.$this->table().' WHERE MAIL = :mail');
        $q->bindValue(':mail', $mail);
        $q->execute();

        $user = $q->fetch(PDO::FETCH_ASSOC);

        return (is_array($user) ? new Users($user) : null);
    }
}
?>