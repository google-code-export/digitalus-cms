<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Forrest Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Content
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Digitalus Content Form
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        Model_Page
 */
class Digitalus_Content_Form extends Digitalus_Form
{
    const PAGE_ACTION = '/admin/page/edit';

    public function init()
    {
        parent::init();

        $view = $this->getView();

        $front = Zend_Controller_Front::getInstance();

        $page_id = $this->createElement('hidden', 'id', array(
            'required'      => true,
        ));

        $language = $this->createElement('hidden', 'language');

        $name = $this->createElement('text', 'name', array(
            'label'         => $view->getTranslation('Page Name'),
            'required'      => true,
            'filters'       => array('StringTrim', 'StripTags'),
            'validators'    => array(
                array('NotEmpty', true),
                array('StringLength', true, array(4, Model_Page::PAGE_NAME_LENGTH)),
                array('PagenameExistsNot'),
                array('Regex', true, array(
                    'pattern'  => Model_Page::PAGE_NAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_Page::PAGE_NAME_REGEX_NOTMATCH),
                )),
            ),
            'attribs'       => array('size' => 50),
            'order'         => 1,
        ));

        $label = $this->createElement('text', 'label', array(
            'label'         => $view->getTranslation('Page Label'),
            'filters'       => array('StringTrim', 'StripTags'),
            'attribs'       => array('size' => 50),
            'order'         => 2,
        ));

        $headline = $this->createElement('text', 'headline', array(
            'label'         => $view->getTranslation('Page Headline'),
            'filters'       => array('StringTrim', 'StripTags'),
            'attribs'       => array('size' => 50),
            'order'         => 3,
        ));

        $submit = $this->createElement('submit', 'submit', array(
            'label'         => $view->getTranslation('Save Changes'),
            'attribs'       => array('class' => 'submit'),
            'order'         => 1000,            // i would assume this is the end
        ));

        $this->setAction($front->getBaseUrl() . self::PAGE_ACTION);
        $this->addElement($page_id)
             ->addElement($language)
             ->addElement($name)
             ->addElement($label)
             ->addElement($headline)
             ->addElement($submit)
             ->setAttrib('enctype', 'multipart/form-data')
             ->setDecorators(array(
                 'FormElements',
                 'Form',
                 array('FormErrors', array('placement' => 'prepend'))
             ))
             ->setDisplayGroupDecorators(array(
                 'FormElements',
                 'Fieldset'
             ));

    }

    public function loadFromTemplate($template)
    {
        $control = new Digitalus_Content_Control($this);
        $control->registerControlsFromTemplate($template);
    }

    public function modifyEditActionForm()
    {
        $view = $this->getView();

        $submitButton = $this->getElement('submit');
        $this->removeElement('submit');

        $controls = $this->getElements();

        // get all of the controls and load them into groups
        foreach ($controls as $control) {
            $rel      = $control->getAttrib('rel');
            $group    = (!empty($rel) ? $rel : 'main');
            $groups[$group][] = $control->getName();
        }
        // create "main" display group
        $this->addDisplayGroup(
            $groups['main'],
            'main',
            array('legend' => $view->getTranslation('main items'))
        );
        unset($groups['main']);

        // create other display groups
        if (is_array($groups)) {
            foreach ($groups as $key => $controls) {
                $this->addDisplayGroup($controls, $key);
            }
        }

        $displayGroups = $this->getDisplayGroups();
        // loop throgh display groups and add submit button
        foreach ($displayGroups as $title => $group) {
            $id = $title . '_content_pane';
            $title = $view->getTranslation(ucwords($title)) . ' ' . $view->getTranslation('Content');
            $contentPanes[$id] = $title;
            $group->setName($id)
                  ->setAttrib('id', $id)
                  ->setLegend($title)
                  ->addElement($submitButton);
        }
        return $contentPanes;
    }

}