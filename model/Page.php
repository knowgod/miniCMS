<?php

/**
 * Description of Page
 *
 * @author arkadij
 */
class model_Page extends model_Abstract
{

    protected $_template;

    public function __construct($modelName, $template = NULL)
    {
        parent::__construct($modelName);
        if (!empty($template)) {
            $this->setTemplate($template);
        }
    }

    public function getTemplate()
    {
        return $this->_template;
    }

    public function setTemplate($template)
    {
        $path = app::getBaseDir() . 'view' . DS . $template;
        if (file_exists($path)) {
            $this->_template = $path;
        } else {
            app::log('Wrong template file!', app::LOG_LEVEL_ERROR);
        }
    }

    public function render()
    {
        $this->getHeader();
        if ($this->_template) {
            include $this->_template;
        }
        $this->getFooter();
    }

    public function getHeader()
    {
        $path = app::getBaseDir() . 'view' . DS . 'header.phtml';
        if (file_exists($path)) {
            include $path;
        }
    }

    public function getFooter()
    {
        $path = app::getBaseDir() . 'view' . DS . 'footer.phtml';
        if (file_exists($path)) {
            include $path;
        }
    }

}

?>
