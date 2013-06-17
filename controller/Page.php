<?php

/**
 * Description of controller_Page
 *
 * @author arkadij
 */
class controller_Page extends controller_Abstract
{

    const PAGE_ID_HOME = '-1';
    const PAGE_ID_CREATE = '-2';
    const PAGE_KEY_HOME = 'home';

    public function viewAction()
    {
        $page_id = $this->_getRequest()->getParam('page', self::PAGE_ID_HOME);
        if (self::PAGE_ID_HOME == $page_id) {
            $page = $this->getPage()->load(self::PAGE_KEY_HOME, 'key');
        } else {
            $page = $this->getPage()->load($page_id);
        }
        if (0 == $page->id) {
            $this->noRouteAction();
            return;
        }
        $page->setData('user', $this->getUser());

        if (self::PAGE_ID_CREATE == $page_id) {
            $this->_getRequest()->setParam('page_id', $page_id);
            $this->editAction();
            return;
        }

        $page->setTemplate('content.phtml');
        parent::viewAction();
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

}

?>
