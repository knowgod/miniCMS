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
        $page_id = $this->_getRequest()->getParam('id', self::PAGE_ID_HOME);
        if (self::PAGE_ID_HOME == $page_id) {
            $page_key = $this->_getRequest()->getParam('key', self::PAGE_KEY_HOME);
            $page = $this->getPage()->load($page_key, 'key');
        } else {
            $page = $this->getPage()->load($page_id);
        }
        if (0 == $page->id) {
            $this->noRouteAction();
            return;
        }
        $page->setData('user', $this->getUser());

        $page->setTemplate('content.phtml');
        parent::viewAction();
    }

    public function saveAction()
    {
        app::log($this->_getRequest()->getData()); //!!!!
        $data = $this->_getRequest()->getParam('edit_page');
        $model = $this->getPage()->load($data['id']);
        if ($model->id) {
            $model->addData($data)->save();
        }
        $this->_redirectBack();
    }

    public function newAction()
    {
        $title = $this->_getRequest()->getParam('title', 'New Page');
        $model = app::getModel('page')
            ->setData(array(
            'title' => $title,
            'key' => model_Page::prepareKey($title),
        ));
        $model->save();
        $path = explode('/', $this->_getRequest()->getParam('path', ''));
        $path[] = $model->id;
        $model->setData('path', implode('/', $path))->save();
        $this->_getRequest()->setParam('id', $model->id);
        $this->viewAction();
    }

    public function deleteAction()
    {

    }

}

?>
