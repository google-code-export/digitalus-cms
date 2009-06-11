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

        $template = $this->createElement('select','content_template');
        $template->setLabel($this->getView()->getTranslation('Content Template') . ':');
        $template->addMultiOption('default', $this->getView()->getTranslation('Default'));

        $availableTemplates = Digitalus_Filesystem_File::getFilesByType(APPLICATION_PATH . '/public/views/scripts/layouts/sublayouts', 'phtml', false, false);
        if (is_array($availableTemplates) && count($availableTemplates) > 0) {
            foreach ($availableTemplates as $t) {
                $template->addMultiOption($t, $this->getView()->getTranslation($t));
            }
        }
        $template->setOrder(2);
        $this->addElement($template);

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