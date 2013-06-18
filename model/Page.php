<?php

/**
 * This is used to display any page
 *
 * @author arkadij
 */
class model_Page extends model_Abstract
{

    protected $_template;

    /**
     * Construct model.
     * 
     * Can set template on construction
     *
     * @param string $modelName
     * @param string $template
     */
    public function __construct($modelName, $template = NULL)
    {
        parent::__construct($modelName);
        if (!empty($template)) {
            $this->setTemplate($template);
        }
    }

    /**
     * Get the name of template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Preapare the template file
     *
     * @param string $template
     * @return model_Page
     */
    public function setTemplate($template)
    {
        $path = app::getBaseDir() . 'view' . DS . $template;
        if (file_exists($path)) {
            $this->_template = $path;
        } else {
            app::log('Wrong template file!', app::LOG_LEVEL_ERROR);
        }
        return $this;
    }

    /**
     * Render the page output
     *
     * @return model_Page
     */
    public function render()
    {
        $this->getHeader();
        if ($this->_template) {
            include $this->_template;
        }
        $this->getFooter();
        return $this;
    }

    /**
     *
     * Render the page header
     */
    public function getHeader()
    {
        $path = app::getBaseDir() . 'view' . DS . 'header.phtml';
        if (file_exists($path)) {
            include $path;
        }
    }

    /**
     *
     * Render the page footer
     */
    public function getFooter()
    {
        $path = app::getBaseDir() . 'view' . DS . 'footer.phtml';
        if (file_exists($path)) {
            include $path;
        }
    }

    public function getMenuItems()
    {
        $collection = $this->_getCollection('path');
        app::log($collection); //!!!!
        $menuItems = array();
        foreach ($collection as $id => $item) {
            $this->_setTreeNode($menuItems, $item);
        }
        app::log($menuItems); //!!!!
        return $menuItems;
    }

    protected function _setTreeNode(&$node, $item, $path = '')
    {
        if (empty($path)) {
            $path = explode('/', $item->path);
        }
        if (count($path) > 1) {
            $nodeId = array_shift($path);
            $this->_setTreeNode($node[$nodeId]['children'], $item, $path);
        } else {
            $node[$item->id]['item'] = $item;
        }
    }

    public function renderMenu($items, $elemContainer = 'ul', $elemNode = 'li', $elemTitle = 'span', $level = 0)
    {
        $output = "<{$elemContainer}>";
        foreach ($items as $item) {
            $output .= "<{$elemNode} class=\"level-{$level}\">";
            if (isset($item['item'])) {
                $output .= "<{$elemTitle} class=\"level-{$level}\"  >" . $item['item']->title . "</{$elemTitle}>";
            }
            if (isset($item['children'])) {
                $output .= $this->renderMenu($item['children'], $elemContainer, $elemNode, $elemTitle, ++$level);
            }
            $output .= "</{$elemNode}>";
        }
        $output .= "</{$elemContainer}>";
        return $output;
    }

}

?>
