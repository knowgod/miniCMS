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
            if (0 == $page->id) {
                if ($this->getUser()->isAdmin()) {
                    $page_id = self::PAGE_ID_CREATE;
                } else {
                    $this->noRouteAction();
                }
                return;
            } else {
                $page->setTemplate('content.phtml');
            }
        }
        if (self::PAGE_ID_CREATE == $page_id) {
            $this->_getRequest()->setParam('page_id', $page_id);
            $this->editAction();
            return;
        }
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
