<?php

/**
 * Description of controller_User
 *
 * @author arkadij
 */
class controller_User extends controller_Abstract
{

    public function loginAction()
    {
        app::log(array($this->_getRequest(), $this->getSessionName())); //!!!!
        $params = $this->_getRequest()->getParam('login');
        $userId = $this->getUser()->login($params['name'], $params['pass']);
        if (FALSE !== $userId) {
            $oldSessionData = $_SESSION;
            $oldSessionName = session_name();
            session_write_close();
            session_name(self::SESS_NAME_PREFIX_LOGGED . $userId);
            session_start();
            $this->_sessionName = session_name();
            unset($_COOKIE[$oldSessionName]);
            $_SESSION = $oldSessionData;
            $_SESSION['user_id'] = $userId;
            $_SESSION['logged_in'] = TRUE;
        }
        $this->_getRequest()->unsetParam('login');
        app::log(array($_SESSION, $_SERVER['HTTP_REFERER'], $this->_sessionName, $_COOKIE)); //!!!!
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function viewAction()
    {
        parent::viewAction();
        app::log($this->_user); //!!!!
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

}

?>