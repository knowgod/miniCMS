<?php

/**
 * Description of User
 *
 * @author arkadij
 */
class model_User extends model_Abstract
{

    public function load($id, $field = NULL)
    {
        $model = parent::load($id, $field);
        return $model;
    }

    public function getUserLevel()
    {
        /**
         * Implement check
         */
        return 1;
    }

    public function isAdmin()
    {
        return (3 == $this->getUserLevel());
    }

    public function login($name, $pass)
    {
        $this->load($name, 'name');
        $userId = FALSE;
        if ($this->id || $this->getPasswordHash($pass) == $this->pass) {
            $userId = $this->id;
        }
        app::log(array($this, $userId)); //!!!!
        return $userId;
    }

    public static function getPasswordHash($password)
    {
        return md5($password . $password);
    }

}

?>
