<?php
class Search_Form extends Digitalus_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

        $view = $this->getView();

        $this->setAction($view->getBaseUrl() . Digitalus_Uri::get())
             ->setAttrib('id', 'searchForm');

        $submitSearchForm = $this->createElement('hidden', 'submitSearchForm', array(
            'value'         => 1,
        ));

        $keywords = $this->createElement('text', 'keywords', array(
            'required'      => true,
            'label'         => $view->getTranslation('Keywords'),
            'attribs'       => array('size' => 50),
            'validators'    => array(
                array('NotEmpty', true),
             ),
            'errorMessages' => array($view->getTranslation('Please provide a keyword to search for!')),
        ));

        $submit = $this->createElement('submit', 'submit', array(
            'label'         => $view->getTranslation('Search'),
            'attribs'       => array('class' => 'submit'),
        ));

        $this->addElement($submitSearchForm)
             ->addElement($keywords)
             ->addElement($submit)
             ->addDisplayGroup(array('form_instance', 'submitSearchForm', 'keywords', 'submit'),
                 'guestbookGroup',
                 array('legend' => $view->getTranslation('Search Site'))
             );
    }
}