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
        app::log($this->_getRequest());
        $params = $this->_getRequest()->getParam('login');
        $this->getUser()->login($params['name'], $params['pass']);
        $this->_getRequest()->unsetParam('user');
        header('location', $_SERVER['HTTP_REFERER']);
    }

    public function viewAction()
    {
        parent::viewAction();
        app::log($this->_user);
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

}

?>
