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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Form.php Tue Dec 25 19:38:20 EST 2007 19:38:20 forrest lyman $
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
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
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

        $this->setAction($front->getBaseUrl() . self::PAGE_ACTION);

        $page_id = $this->createElement('hidden', 'id', array(
            'required'      => true,
        ));

        $language = $this->createElement('hidden', 'language');

        $name = $this->createElement('text', 'name', array(
            'label'         => $view->getTranslation('Page Name'),
            'required'      => true,
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('NotEmpty', true),
                array('Regex', true, array(
                    'pattern'  => Model_Page::PAGE_NAME_REGEX,
                    'messages' => array('regexNotMatch' => Model_Page::PAGE_NAME_REGEX_NOTMATCH),
                )),
            ),
        ));

        $label = $this->createElement('text', 'label', array(
            'label'         => $view->getTranslation('Page Label'),
            'filters'       => array('StringTrim'),
        ));

        $headline = $this->createElement('text', 'headline', array(
            'label'         => $view->getTranslation('Page Headline'),
            'filters'       => array('StringTrim'),
        ));

        $submit = $this->createElement('submit', 'submit', array(
            'label'         => $view->getTranslation('Save Changes'),
            'attribs'       => array('class' => 'submit'),
            'order'         => 1000,            // i would assume this is the end
        ));

        $this->addElement($page_id)
             ->addElement($language)
             ->addElement($name)
             ->addElement($label)
             ->addElement($headline)
             ->addElement($submit);
    }

    public function loadFromTemplate($template)
    {
        $control = new Digitalus_Content_Control($this);
        $control->registerControlsFromTemplate($template);
    }
}