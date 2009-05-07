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
        /*
         * Added by Brad Seefeld on May 6, 2009.
         * First we need to check to see if the incoming link is an absolute
         * address. If it is, we do not want to prepend the baseUrl.
         *
         * Also, check to see if we are targeting an anchor. If anchor, do
         * not prepend base url.
         */

        if (!$this->isRemoteLink($link) && !$this->isAnchorLink($link) && !$this->containsBaseUrl($link)) {
            $link = $this->view->baseUrl . $link;
        }

        $linkParts[] = "<a href='{$link}' class='{$class}'>";

        if (null !== $icon) {
            $linkParts[] = $this->getIcon($icon, $label);
        }
        if (!empty($label)) {
            $linkParts[] = $this->view->GetTranslation((string)$label);
        }
        $linkParts[] = '</a>';
        return implode(null, $linkParts);
    }

    public function isRemoteLink($link)
    {
        if ('http://'  == strtolower(substr($link, 0, 7)) ||
            'https://' == strtolower(substr($link, 0, 8))) {
            return true;
        } else {
            return false;
        }
    }

    public function isAnchorLink($link)
    {
        if (substr($link, 0, 1) == '#') {
            return true;
        } else {
            return false;
        }
    }

    public function containsBaseUrl($link)
    {
        if (substr($link, 0, strlen($this->view->baseUrl)) == $this->view->baseUrl) {
            return true;
        } else {
            return false;
        }
    }

    public function getIcon($icon, $alt)
    {
        $config = Zend_Registry::get('config');
        $this->iconPath = $config->filepath->icons;
        if (substr($this->iconPath, 0, 1) != '/') {
            $this->iconPath = '/' . $this->iconPath;
        }
        $iconPath = $this->view->baseUrl . $this->iconPath;
        return "<img src='{$iconPath}/{$icon}' alt='" . htmlspecialchars($this->view->GetTranslation((string)$alt)) . "' class='icon' />";
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
