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
 * @version     $Id:$
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
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_Form extends Zend_Form
{
    protected $_model;
    protected $_columns;
    protected $_session = null;

    public function __construct($translator = null)
    {
        $this->setMethod('post')
             ->addElementPrefixPaths(array(
                array('prefix' => 'Digitalus_Decorator', 'path' => 'Digitalus/Form/Decorator', 'type' => 'decorator'),
                array('prefix' => 'Digitalus_Filter',    'path' => 'Digitalus/Filter/',        'type' => 'filter'),
                array('prefix' => 'Digitalus_Validate',  'path' => 'Digitalus/Validate',       'type' => 'validate'),
             ))
             ->addPrefixPath('Digitalus_Form_Element', 'Digitalus/Form/Element/', 'element');

        $this->_setTranslator($translator);
        parent::__construct();

        //set instance
        $instance = $this->_addInstance();

        $instanceElement = $this->createElement('hidden', 'form_instance');
        $instanceElement->setValue($instance)
                        ->removeDecorator('Label');
        $this->addElement($instanceElement);
    }

    public function setModel(Zend_Db_Table_Abstract $model)
    {
        $this->_model = $model;
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
        $this->removeElement('id');
        if ($this->isValid($_POST)) {
            $row = $this->_model->createRow();
            $values = $this->getValues();
            foreach ($values as $field => $value) {
               $this->_setField($field, $value, $row, $valueOverride);
            }
            $row->save();
            $row->id = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * you can override a posted value by setting it in the values array
     *
     * @param array $values
     * @return mixed
     */
    public function update($valueOverride = array())
    {
       if ($this->isValid($_POST)) {
            $values = $this->getValues();
            $row = $this->_model->find($values['id'])->current();
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
        if ($request->isPost()) {
            if ($this->isValid($_POST)) {
                return true;
            }
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
#        $session->validInstances->$instance = true;
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
}