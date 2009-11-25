<?php
class Slide_Form extends Digitalus_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElementPrefixPath('Digitalus_Decorator', 'Digitalus/Form/Decorator', 'decorator')
             ->addPrefixPath('Digitalus_Form_Element', 'Digitalus/Form/Element/', 'element')
             ->setMethod('post')
             ->setEnctype('multipart/form-data');

        $id = $this->createElement('hidden', 'id');
        $id->removeDecorator('Label');

        $show = $this->createElement('hidden', 'show_id');
        $show->removeDecorator('Label');

        $title = $this->createElement('text', 'title');
        $title->setLabel($this->getView()->getTranslation('Title') . ':');

        // create new element
        $imagepath = $this->createElement('hidden', 'previewpath');
        // element options
        $imagepath->setLabel($this->getView()->getTranslation('Current Image'))
                  ->setRequired(false)
                  ->setDecorators(array(array('ViewScript', array(
                      'viewScript' => 'slide/render-image.phtml',
                      'class'      => 'partial'
                  ))));

        // create new element
        $imagePreview = $this->createElement('file', 'image_preview');
        // element options
        $imagePreview->setLabel($this->getView()->getTranslation('Image Preview (scaled)'))
                     ->setRequired(false)
                     ->setRequired(false)
                     ->addValidator('Count', false, 1)                      // only one image allowed
                     ->addValidator('Size', false, 204800)                  // maximum 200kB
                     ->addValidator('Extension', false, 'jpg, png, gif');   // only JPEG, PNG, und GIF allowed

        // create new element
        $image = $this->createElement('file', 'image');
        // element options
        $image->setLabel($this->getView()->getTranslation('Image'))
              ->setRequired(false)
              ->addValidator('Count', false, 1)                     // only one image allowed
              ->addValidator('Size', false, 1024000)                // maximum 1000kB
              ->addValidator('Extension', false, 'jpg, png, gif');  // only JPEG, PNG, und GIF allowed

        $caption = $this->createElement('textarea', 'caption');
        $caption->setLabel($this->getView()->getTranslation('Caption'))
                ->setAttrib('cols', 40)
                ->setAttrib('rows', 8);

        $submit = $this->createElement('submit', 'submit');

        // add the elements to the form
        $this->addElement($id)
             ->addElement($show)
             ->addElement($title)
             ->addElement($imagepath)
             ->addElement($imagePreview)
             ->addElement($imagepath)
             ->addElement($image)
             ->addElement($caption)
             ->addElement($submit);
    }
}