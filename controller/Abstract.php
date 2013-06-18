<?php

/**
 * Description of controller_Abstract
 *
 * @author arkadij
 */
abstract class controller_Abstract
{

    const SESS_NAME_PREFIX_LOGGED = 'miniCMS_user_';
    const SESS_NAME_PREFIX_NONLOG = 'miniCMS_guest_';

    protected $_request;
    protected $_page;
    protected $_user;
    protected $_sessionName;

    public function __construct()
    {
        $this->_initSession();
    }

    /**
     *
     * @return lib_Request
     */
    protected function _getRequest()
    {
        if (!$this->_request) {
            $this->_request = new lib_Request();
        }
        return $this->_request;
    }

    protected function _redirect($actionName)
    {
        if (!empty($actionName)) {
            $actionName .= 'Action';
            app::log(get_class_methods($this));
            if (in_array($actionName, get_class_methods($this))) {
                $this->$actionName();
            }
        }
    }

    /**
     *
     * @param string $template
     * @return model_Page
     */
    public function getPage($template = NULL)
    {
        if (!$this->_page) {
            $this->_page = app::getModel('page', $template);
        }
        return $this->_page;
    }

    /**
     *
     * @return model_User
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = app::getModel('user');
            if (isset($_SESSION['user_id'])) {
                $this->_user->load($_SESSION['user_id']);
            }
        }
        return $this->_user;
    }

    public function noRouteAction()
    {
        $this->_getRequest()->setStatus(404);
        $this->getPage('404.phtml')->render();
    }

    public function viewAction()
    {
        $this->getPage()->render();
    }

    public abstract function editAction();

    public abstract function deleteAction();

    protected function _initSession()
    {
        session_name($this->getSessionName());
        session_start();
        $_SESSION['logged_in'] = isset($_SESSION['user_id']) ? TRUE : FALSE;
        app::log($_SESSION); //!!!!
    }

    public function getSessionName()
    {
        if (!$this->_sessionName) {
            app::log($_COOKIE); //!!!!
            foreach ($_COOKIE as $cookie => $val) {
                if (0 === strpos($cookie, self::SESS_NAME_PREFIX_NONLOG) || 0 === strpos($cookie, self::SESS_NAME_PREFIX_LOGGED)) {
                    $this->_sessionName = $cookie;
                    break;
                }
            }
            if (!$this->_sessionName) {
                $this->_sessionName = self::SESS_NAME_PREFIX_NONLOG . uniqid();
            }
        }
        return $this->_sessionName;
    }

}

?>
