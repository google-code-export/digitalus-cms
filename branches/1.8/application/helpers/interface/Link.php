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
 * @uses viewHelper Digitalus_View_Helper_Interface
 */
class Digitalus_View_Helper_Interface_Link {

    /**
     * @var Zend_View_Interface
     */
    public $view;
    public $link;
    public $baseUrl;

    /**
     *
     */
    public function link($label, $link, $icon = null, $class = 'link')
    {
        $this->link = Digitalus_Toolbox_String::stripLeading('/', $link);
        $this->baseUrl = Digitalus_Toolbox_String::stripLeading('/', $this->view->getBaseUrl());
        
        // clean the link
        if($this->isRemoteLink($link) || $this->isAnchorLink($link)) {
            $cleanLink = $link;
        } else {
            $cleanLink = '/' . $this->addBaseUrl($this->link);
        }
       
        $linkParts[] = "<a href='{$cleanLink}' class='{$class}'>";
       
        if (null !== $icon) {
            $linkParts[] = $this->getIcon($icon, $label);
        }
        if (!empty($label)) {
            $linkParts[] = $this->view->getTranslation((string)$label);
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
    
    public function addBaseUrl($path)
    {
        if(!empty($this->baseUrl)) {
            if(substr($path, 0, strlen($this->baseUrl) != $this->baseUrl)) {
                return $this->baseUrl . '/' . $path;
            }
        }
        return $path;
    }
    
    public function isAnchorLink($link)
    {
        if(strpos($link, '#') === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    
    public function isRemoteLink($link)
    {
        if(strpos($link, 'http') === FALSE) {
            return false;
        } else {
            return true;
        } 
    }
    
    public function getIcon($icon, $alt)
    {
        $config = Zend_Registry::get('config');
        $this->iconPath = $config->filepath->icons;
        $iconPath = $this->iconPath;
        $iconPath = $this->addBaseUrl($iconPath . '/' . $icon);
        return "<img src='/{$iconPath}' alt='" . htmlspecialchars($alt) . "' class='icon' />";
    }
}
