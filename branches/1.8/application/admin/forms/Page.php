<?php
class Admin_Form_Page extends Digitalus_Form
{
    public function init()
    {
        $id = $this->createElement('hidden', 'id');
        $id->setDecorators(array('ViewHelper'));
        $this->addElement($id);

        $name = $this->createElement('text', 'page_name');
        $name->addFilter('StripTags');
        $name->setRequired(true);
        $name->setLabel('Page Name: ');
        $name->setAttrib('size', 50);
        $name->setOrder(0);
        $this->addElement($name);

        $parentId = $this->createElement('select', 'parent_id');
        $parentId->setLabel($this->getView()->getTranslation('Parent page') . ':');
        $mdlIndex = new Model_Page();
        $index = $mdlIndex->getIndex(0, 'name');
        $parentId->addMultiOption(0, $this->getView()->getTranslation('Site Root'));
        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $parentId->addMultiOption($id, $page);
            }
        }
        $parentId->setOrder(1);
        $this->addElement($parentId);

        $contentTemplate = $this->createElement('select','content_template');
        $contentTemplate->setLabel($this->getView()->getTranslation('Template') . ':');

        $templateConfig = Zend_Registry::get('config')->template;        
        $templates = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public');
        foreach ($templates as $template) {
            $designs = Digitalus_Filesystem_File::getFilesByType(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public/' . $template . '/designs', 'xml');
            if(is_array($designs)) {
                foreach ($designs as $design) {
                    $design = Digitalus_Toolbox_Regex::stripFileExtension($design);
                    $contentTemplate->addMultiOption($template . '_' . $design, $template . ' / ' . $design);
                }
            }
        }
        $contentTemplate->setOrder(2);
        $this->addElement($contentTemplate);

        $continue = $this->createElement('checkbox', 'continue_adding_pages');
        $continue->setLabel($this->getView()->getTranslation('Continue adding pages') . '?');
        $continue->setOrder(3);
        $this->addElement($continue);

        $submit = $this->createElement('submit', $this->getView()->getTranslation('Submit'));
        $submit->setOrder(1000);
        $this->addElement($submit);
    }
}
?>