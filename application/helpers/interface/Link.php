<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * Link helper
 *
 * @uses viewHelper DSF_View_Helper_Interface
 */
class DSF_View_Helper_Interface_Link {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    public $iconPath;

    /**
     *
     */
    public function link($label, $link, $icon = null, $class = 'link')
    {
        $config = Zend_Registry::get('config');
        $this->iconPath = $config->filepath->icons;
        $linkParts[] = "<a href='{$link}' class='{$class}'>";
        $iconPath = $this->view->baseUrl . $this->iconPath;
        if (null !== $icon) {
            $linkParts[] = "<img src='/{$iconPath}/{$icon}' alt='({$label}) ' class='icon' />";
        }
        if (!empty($label)) {
            $linkParts[] = $this->view->GetTranslation((string)$label);
        }
        $linkParts[] = '</a>';
        return implode(null, $linkParts);
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}