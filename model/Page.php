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
     * Encode HTML before saving
     *
     * @return model_Page
     */
    public function save()
    {
        $this->content = htmlspecialchars($this->content);
        return parent::save();
    }

    public static function prepareKey($origin)
    {
        $key = urlencode(strtolower(preg_replace('#[^\w\d]#', '_', $origin))) . uniqid();
        return $key;
    }

    /**
     * Decode HTML after loading
     *
     * @param mixed $id
     * @param string $field
     * @return model_Page
     */
    public function load($id, $field = NULL)
    {
        parent::load($id, $field);
        $this->content = htmlspecialchars_decode($this->content);
        return $this;
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
     * Prepare the template file
     *
     * All templates are stored in folder ROOT/view
     *
     * @param string $template
     * @return model_Page
     */
    public function setTemplate($template)
    {
        if (!$this->userCanView()) {
            $template = 'restricted.phtml';
        }

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
     * Check if the page is available for given user level
     *
     * @param int $userLevel If not set - choose current user level
     * @return bool
     */
    public function userCanView($userLevel = NULL)
    {
        if (is_null($userLevel)) {
            $userLevel = $this->user->user_level;
        }
        if (!$userLevel) {
            $userLevel = model_User::LEVEL_GUEST;
        }
        return ($this->user_level <= $userLevel);
    }

    /**
     * Check if user can edit
     *
     * @param int $userLevel
     * @return bool
     */
    public function userCanEdit($userLevel = NULL)
    {
        if (is_null($userLevel)) {
            $userLevel = $this->user->user_level;
        }
        return ($userLevel >= model_User::LEVEL_ADMIN);
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
        $menuItems = array();
        foreach ($collection as $id => $item) {
            $this->_setTreeNode($menuItems, $item);
        }
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

    public function renderMenu($items, $elemContainer = 'ul', $elemNode = 'li', $level = 0)
    {
        $rootId = (0 == $level) ? ' id="cssmenu"' : '';
        $output = "<{$elemContainer}{$rootId}>";
        foreach ($items as $itemId => $_item) {
            if ($_item['item']->userCanView($this->user->user_level)) {
                $output .= "<{$elemNode}>";
                if (isset($_item['item'])) {
                    $active = ($itemId = $this->id) ? ' class="active"' : '';
                    $link = app::getUrl(array('page' => 'view', 'key' => $_item['item']->key));
                    $output .= "<a href=\"{$link}\" {$active}>" . $_item['item']->title . "</a>";
                }
                if (isset($_item['children'])) {
                    $output .= $this->renderMenu($_item['children'], $elemContainer, $elemNode, ++$level);
                }
                $output .= "</{$elemNode}>";
            }
        }
        $output .= "</{$elemContainer}>";
        return $output;
    }

}

?>
