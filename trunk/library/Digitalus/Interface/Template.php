<?php
class Digitalus_Interface_Template
{
    public function load($template)
    {
        if (is_object($template)) {
            return $template;
        } else if (file_exists($template)) {
            $form = new Digitalus_Content_Form();
            $view = $form->getView();
            $templateName = basename($template);
            $layoutPath = Digitalus_Toolbox_String::getParentFromPath($template);
            $view->addScriptPath($layoutPath);
            $fileContents = $view->render($templateName);
            $cleanFile = preg_replace('/(<\?{1}[pP\s]{1}.+\?>)/', '', $fileContents);
            return simplexml_load_string($cleanFile);
        } else {
            return simplexml_load_string($template);
        }
    }

    public function getControls($template)
    {
        $template = $this->load($template);
        return $template->xpath('//digitalusControl');
    }

    public function getForm($template, $formInstance = null, $content = null)
    {
        if ($formInstance == null) {$formInstance = new Digitalus_Content_Form();}
        $view = $formInstance->getView();
        $controls = $this->getControls($template);
        if ($controls) {
            foreach ($controls as $control) {
                $attribs = $control->attributes();
                $id = (string)$attribs['id'];
                $type = (string)$attribs['type'];
                if (isset($attribs['required'])) {
                    $required = true;
                    unset($attribs['required']);
                } else {
                    $required = false;
                }

                if (isset($attribs['label'])) {
                    $label = (string)$attribs['label'];
                    unset($attribs['label']);
                } else {
                    $label = $view->getTranslation($id);
                    $label = ucwords(str_replace('_', ' ', $label));
                }


                $control = $formInstance->createElement($type, $id, $attribs);
                $control->setLabel($label);
                $control->setRequired($required);
                $control->setAttrib('rel', isset($attribs['group']) ? (string)$attribs['group'] : 'main');
                if (isset($content[$id])) {
                    $control->setValue($content['id']);
                }
                $formInstance->addElement($control);

                // set the display group
                // $displayGroup = (isset($attribs['group']))? (string)$attribs['group'] : 'main';
                // $formInstance->addDisplayGroup(array($control), $displayGroup);

            }
        }
        return $formInstance;
    }

    public function getPageData($template, $page, $scope = 'public')
    {
        $config = Zend_Registry::get('config')->template;
        return $this->load(BASE_PATH . '/' . $config->pathToTemplates . '/' . $scope . '/' . $template . '/pages/' . $page . '.xml');
    }
}
?>