<?php

/**
 * Description of User
 *
 * @author arkadij
 */
class model_User extends model_Abstract
{

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

}

?>
