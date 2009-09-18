<?php
class Admin_Form_Page extends Digitalus_Form
{
    public function init()
    {
        $id = $this->createElement('hidden', 'id');
        $id->setDecorators(array('ViewHelper'));
        $this->addElement($id);

        $name = $this->createElement('text', 'page_name');
        $name->addFilter('StripTags')
             ->setRequired(true)
             ->setLabel($this->getView()->getTranslation('Page Name'))
             ->setAttrib('size', 50)
             ->setOrder(0);
        $this->addElement($name);

        $parentId = $this->createElement('select', 'parent_id');
        $parentId->setLabel($this->getView()->getTranslation('Parent page') . ':')
                 ->addMultiOption(0, $this->getView()->getTranslation('Site Root'))
                 ->setOrder(1);
        $mdlIndex = new Model_Page();
        $index = $mdlIndex->getIndex(0, 'name');
        if (is_array($index)) {
            foreach ($index as $id => $page) {
                $parentId->addMultiOption($id, $page);
            }
        }
        $this->addElement($parentId);

        $contentTemplate = $this->createElement('select', 'content_template');
        $contentTemplate->setLabel($this->getView()->getTranslation('Template') . ':')
                        ->setOrder(2);

        $templateConfig = Zend_Registry::get('config')->template;
        $templates = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public');
        foreach ($templates as $template) {
            $designs = Digitalus_Filesystem_File::getFilesByType(BASE_PATH . '/' . $templateConfig->pathToTemplates . '/public/' . $template . '/pages', 'xml');
            if (is_array($designs)) {
                foreach ($designs as $design) {
                    $design = Digitalus_Toolbox_Regex::stripFileExtension($design);
                    $contentTemplate->addMultiOption($template . '_' . $design, $this->getView()->getTranslation($template) . ' / ' . $this->getView()->getTranslation($design));
                }
            }
        }
        $this->addElement($contentTemplate);

        $continue = $this->createElement('checkbox', 'continue_adding_pages');
        $continue->setLabel($this->getView()->getTranslation('Continue adding pages') . '?')
                 ->setOrder(3);
        $this->addElement($continue);

        $showOnMenu = $this->createElement('checkbox', 'show_on_menu');
        $showOnMenu->setLabel($this->getView()->getTranslation('Show Page on menu') . '?')
                ->setOrder(4);
        $this->addElement($showOnMenu);

        $publish = $this->createElement('checkbox', 'publish_pages');
        $publish->setLabel($this->getView()->getTranslation('Publish page instantly') . '?')
                ->setOrder(5);
        $this->addElement($publish);

        $submit = $this->createElement('submit', $this->getView()->getTranslation('Submit'));
        $submit->setOrder(1000);
        $this->addElement($submit);
    }
}