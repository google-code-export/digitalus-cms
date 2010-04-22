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
 * @version     $Id: Form.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_Form
 */
require_once 'Zend/Form.php';

/**
 * Digitalus Form
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_Form extends Zend_Form
{
    const REQ_SUFFIX = '<sup title="This field is mandatory.">*</sup>';

    const CSS_FRAMEWORK = '';       // either empty, yaml or blueprint --> respect their licenses!!

    protected $_model;
    protected $_columns;
    protected $_session = null;
    protected $_primaryIndex = '';
    protected $_standardDecorators = array(
        'form' => array(
            'FormElements',
            'Form'
        ),
        'group' => array(
            'FormElements',
            'Fieldset'
        ),
        'standard' => array(
            'ViewHelper',
            'Errors',
            'Label',
        ),
    );

    protected $_errorClass = 'error';

    public function init()
    {
        parent::init();

        $this->setMethod('post')
             ->setAttrib('accept-charset', 'UTF-8')
             ->clearDecorators()
             ->addPrefixPaths(array(
                array('prefix' => 'Digitalus_Form_Decorator', 'path' => 'Digitalus/Form/Decorator', 'type' => 'decorator'),
                array('prefix' => 'Digitalus_Form_Element',   'path' => 'Digitalus/Form/Element/',  'type' => 'element'),
             ))
             ->addElementPrefixPaths(array(
                array('prefix' => 'Digitalus_Form_Decorator', 'path' => 'Digitalus/Form/Decorator', 'type' => 'decorator'),
                array('prefix' => 'Digitalus_Filter',         'path' => 'Digitalus/Filter/',        'type' => 'filter'),
                array('prefix' => 'Digitalus_Validate',       'path' => 'Digitalus/Validate',       'type' => 'validate'),
             ));

        $this->_setStandardDecorators();

        //set instance
        $instance = $this->_addInstance();
        $instanceElement = $this->createElement('hidden', 'form_instance', array(
            'value'         => $instance,
            'decorators'    => array('ViewHelper'),
        ));
        $this->addElement($instanceElement);
    }

    public function setModel(Zend_Db_Table_Abstract $model)
    {
        $this->_model = $model;
        $this->_setPrimaryIndex($this->_model->primaryIndex);
        $info = $model->info();
        $this->_columns = $info['cols'];
    }

    public function populateFromModel($id)
    {
        $row = $this->_model->find($id)->current();
        if ($row) {
            parent::populate($row->toArray());
        }
    }

    /**
     * you can override a posted value by setting it in the values array
     *
     * @param array $values
     * @return mixed
     */
    public function create($valueOverride = array())
    {
        $elements = $this->getElements();
        if (in_array('id', $elements)) {
            $this->removeElement('id');
        }
        if ($this->isValid($_POST)) {
            $row = $this->_model->createRow();
            $values = $this->getValues();
            foreach ($values as $field => $value) {
               $this->_setField($field, $value, $row, $valueOverride);
            }
            $row->save();
            $primaryIndex = $this->_getPrimaryIndex();
            $row->$primaryIndex = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
            return $row;
        }
        return false;
    }

    /**
     * you can override a posted value by setting it in the values array
     *
     * @param array $values
     * @return mixed
     */
    public function update($primaryIndex = null, $valueOverride = array())
    {
        if ($this->isValid($_POST)) {
            $values = $this->getValues();
            if (!isset($primaryIndex) || empty($primaryIndex)) {
                $primaryIndex = $this->_getPrimaryIndex();
                $primaryIndex = $values[$primaryIndex];
            }
            $row = $this->_model->find($primaryIndex)->current();
            if ($row) {
                foreach ($values as $field => $value) {
                    $this->_setField($field, $value, $row, $valueOverride);
                }
                $row->save();
                return $row;
            }
        }
        return false;
    }

    private function _setField($field, $value, $row, $values)
    {
        if (in_array($field, $this->_columns)) {
            if (isset($values[$field])) {
                $value = $values[$field];
            }
            $row->$field = $value;
            return true;
        }
        return false;
    }

    public function getRow()
    {
        $values = $this->getValues();
        if (isset($values['id'])) {
            $id = $values['id'];
        } else {
            $id = $this->_model->getAdapter()->lastInsertId();
        }
        return $this->_model->find($id)->current();
    }

    public function validatePost()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request->isPost() && $this->isValid($_POST)) {
            return true;
        }
        return false;
    }


    public function isSubmitted()
    {
        if (Digitalus_Filter_Post::has('form_instance')) {
            $instance = Digitalus_Filter_Post::get('form_instance');
            if ($this->_isValidInstance($instance)) {
                return true;
            }
        }
        return false;
    }

    protected function _getSession()
    {
        if ($this->_session == null) {
            $this->_session = new Zend_Session_Namespace(get_class($this) . '_Instance');
        }
        return $this->_session;
    }

    protected function _addInstance()
    {
        $instance = $this->_getInstance();
        $session = $this->_getSession();
        $session->validInstances = array($instance => true);
        return $instance;
    }

    protected function _getInstance()
    {
        //generate GUID for form instance
        return md5(get_class($this) . time());
    }

    protected function _isValidInstance($instance)
    {
        $session = $this->_getSession();
        if (isset($session->validInstances[$instance]) && $session->validInstances[$instance] === true) {
            return true;
        } else {
            return false;
        }
    }

    protected function _removeInstance($instance)
    {
        $session = $this->_getSession();
        $session->validInstances[$instance] = false;
    }

    protected function _setTranslator($adapter = null)
    {
        if (empty($adapter)) {
            $adapter = Zend_Registry::get('Zend_Translate');

            $request    = Zend_Controller_Front::getInstance()->getRequest();
            $module     = $request->getModuleName();
            $controller = $request->getControllerName();
            // Add translation file depending on current module ('public' or 'admin')
            if ('public' == $module || 'public' == $controller) {
                $key = Digitalus_Language::getLanguage();
            } else {
                $key = Digitalus_Language::getAdminLanguage();
            }
            if (!empty($key)) {
                // Get site config
                $config = Zend_Registry::get('config');

                $languageFiles = $config->language->translations->toArray();
                $languagePath  = $config->language->path . '/form/' . $languageFiles[$key] . '.form.csv';
                if (is_file($languagePath)) {
                    $adapter->addTranslation($languagePath, $key);
                }
            }
        } else {
            self::setDefaultTranslator($adapter);
        }
    }

    protected function _setPrimaryIndex($primaryIndex)
    {
        $this->_primaryIndex = strtolower(trim($primaryIndex));
    }

    protected function _getPrimaryIndex()
    {
        return $this->_primaryIndex;
    }

    protected function _setDecorator(Zend_Form_Element $element)
    {
        $search  = array('submit', 'reset',  'checkbox', 'radio',    'textarea', 'password');
        $replace = array('button', 'button', 'checkbox', 'checkbox', 'text',     'text');
        $type = strtolower(str_replace('Zend_Form_Element_', '', $element->getType()));
        $type = str_replace($search, $replace, $type);
        if (key_exists($type, $this->_standardDecorators)) {
            $element->setDecorators($this->_standardDecorators[$type]);

            if (method_exists($element, 'hasErrors') && $element->hasErrors()) {
                $decorator = $element->getDecorator('HtmlTag');
                if (is_object($decorator)) {
                    $decorator->setOption('class', $decorator->getOption('class') . ' ' . $this->_errorClass);
                }
            }
        } else {
            $element->loadDefaultDecorators();
        }
    }

    /**
     * Create an element
     *
     * Acts as a factory for creating elements. Elements created with this
     * method will not be attached to the form, but will contain element
     * settings as specified in the form object (including plugin loader
     * prefix paths, default decorators, etc.).
     *
     * @param  string $type
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form_Element
     */
    public function createElement($type, $name, $options = null)
    {
        $this->_setStandardDecorators();
        $element = parent::createElement($type, $name, $options);
        if (key_exists($type, $this->_standardDecorators)) {
            $element->setDecorators($this->_standardDecorators[$type]);

            if (method_exists($element, 'hasErrors') && $element->hasErrors()) {
                $decorator = $element->getDecorator('HtmlTag');
                if (is_object($decorator)) {
                    $decorator->setOption('class', $decorator->getOption('class') . ' ' . $this->_errorClass);
                }
            }
        } else {
            $element->loadDefaultDecorators();
        }
        return $element;
    }

    public function render(Zend_View_Interface $view = null)
    {
        $this->setDecorators($this->_standardDecorators['form']);
        $this->setDisplayGroupDecorators($this->_standardDecorators['group']);
        foreach ($this->getElements() as $element) {
            $this->_setDecorator($element);
        }
        return parent::render($view);
    }

    public function getStandardDecorator($type)
    {
        $this->_setStandardDecorators();
        if (isset($this->_standardDecorators[$type])) {
            return $this->_standardDecorators[$type];
        }
        return array();
    }

    protected function _setStandardDecorators()
    {
        $framework = strtolower(self::CSS_FRAMEWORK);
        switch ($framework) {
            case 'blueprint':
            case 'yaml':
                $method = '_set' . ucfirst(self::CSS_FRAMEWORK) . 'Decorators';
                $this->$method();
            case 'yaml':
                $this->addAttribs(array('class' => 'yform'));
                break;
            default:
        }
    }

    protected function _setBlueprintDecorators()
    {
        $this->_standardDecorators['text'] = array(
            'ViewHelper',
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'p'))
        );
        $this->_standardDecorators['captcha'] = array(
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'p'))
        );
        $this->_standardDecorators['hidden'] = array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'p'))
        );
        $this->_standardDecorators['select'] = array(
            'ViewHelper',
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'p'))
        );
        $this->_standardDecorators['checkbox'] = array(
            'ViewHelper',
            array('Label',       array('placement' => 'append', 'requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors',    array('placement' => 'prepend')),
            array('Description', array('tag' => 'label', 'placement' => 'prepend', 'separator' => '<br />')),
            array('HtmlTag',     array('tag' => 'p'))
        );
        $this->_standardDecorators['button'] = array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'p'))
        );
    }

    protected function _setYamlDecorators()
    {
        $this->_standardDecorators['text'] = array(
            'ViewHelper',
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'div', 'class' => 'type-text'))
        );
        $this->_standardDecorators['captcha'] = array(
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'div', 'class' => 'type-text'))
        );
        $this->_standardDecorators['hidden'] = array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'type-hidden'))
        );
        $this->_standardDecorators['select'] = array(
            'ViewHelper',
            array('Label',    array('requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors', array('placement' => 'prepend')),
            array('HtmlTag',  array('tag' => 'div', 'class' => 'type-select'))
        );
        $this->_standardDecorators['checkbox'] = array(
            'ViewHelper',
            array('Label',       array('placement' => 'append', 'requiredSuffix' => self::REQ_SUFFIX, 'escape' => false)),
            array('MyErrors',    array('placement' => 'prepend')),
            array('Description', array('tag' => 'label', 'placement' => 'prepend', 'separator' => '<br />')),
            array('HtmlTag',     array('tag' => 'div', 'class' => 'type-check'))
        );
        $this->_standardDecorators['button'] = array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'type-button'))
        );
    }
}