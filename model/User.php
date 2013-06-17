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
        if ($this->id) {
            $match = ($this->getPasswordHash($pass) == $this->pass);
        }
        app::log(array($this, $match));//!!!!
        return $this;
    }

    public static function getPasswordHash($password)
    {
        return md5($password . $password);
    }

}

?>
