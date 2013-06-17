<?php

/**
 * Description of controller_Abstract
 *
 * @author arkadij
 */
abstract class controller_Abstract
{

    private $_request;
    private $_page;
    private $_user;

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
}

?>
