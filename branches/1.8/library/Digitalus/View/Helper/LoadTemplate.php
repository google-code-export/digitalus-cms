<?php
class Digitalus_View_Helper_LoadTemplate extends Zend_View_Helper_Abstract 
{
    public function loadTemplate($scope = 'public', $template = null, $design = null)
    {
        $templateConfig = Zend_Registry::get('config')->template;
        
        if(null == $template) {
            $template = $templateConfig->default->$scope;
        }
        
        if(null == $design) {
            $design = $templateConfig->default->design;
        }
        
        $designFile = BASE_PATH . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template . '/designs/' . $design . '.xml';
        $designConfig = new Zend_Config_Xml($designFile);
        
        // first load all of the style sheets
        $styleArray = $designConfig->styles->toArray();
        
        if(is_array($styleArray)) {
            if(isset($styleArray['stylesheet'])) {
                if(is_array($styleArray['stylesheet'])) {
                    $templateStyles = $styleArray['stylesheet'];
                } else {
                    $templateStyles = array($styleArray['stylesheet']);
                }
            } else {
                $templateStyles = array();
            }
            
            if(isset($styleArray['import'])) {
                if(is_array($styleArray['import'])) {
                    $importStyles = $styleArray['import'];
                } else {
                    $importStyles = array($styleArray['import']);
                }
            } else {
                $importStyles = array();
            }
             
            $templatePath = $this->view->getBaseUrl() . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template;
            foreach ($templateStyles as $style) {
            	$this->view->headLink()->appendStylesheet($templatePath . '/styles/' . $style);
            }
            foreach ($importStyles as $style) {
                if(substr($style, 0, 4) != 'http') {
                    $style = $this->view->getBaseUrl() . '/' . $style;
                }
                $this->view->headLink()->appendStylesheet($style);
            }
            
            $this->view->addScriptPath(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/' . $scope . '/' . $template . '/layouts');
            $this->view->layout()->template = $this->view->render($design . '.phtml');
        }        
    }
}
?>