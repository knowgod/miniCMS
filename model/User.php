<?php

/**
 * User model.
 *
 * Login / logout, check permissions
 *
 * @author arkadij
 */
class model_User extends model_Abstract
{
    /**
     * Users levels:
     */

    const LEVEL_GUEST = '1';
    const LEVEL_GUEST_TEXT = 'guest';
    const LEVEL_USER = '2';
    const LEVEL_USER_TEXT = 'user';
    const LEVEL_ADMIN = '3';
    const LEVEL_ADMIN_TEXT = 'admin';

    /**
     * Perform functionality for logging in.
     *
     * Returns FALSE if name/pass don't match.
     *
     * @param string $name
     * @param string $pass
     * @return int|bool
     */
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

    /**
     * Transform password to hash.
     *
     * @param string $password
     * @return string
     */
    public static function getPasswordHash($password)
    {
        return md5($password . $password);
    }

    public function isLoggedIn()
    {
        return $this->id ? TRUE : FALSE;
    }

}

?>
