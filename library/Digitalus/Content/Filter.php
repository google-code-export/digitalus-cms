<?php
/**
 * DigitalusControl
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
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Digitalus_Abstract
 */
require_once 'Digitalus/Abstract.php';

/**
 * DigitalusAbstract
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        Digitalus_Builder
 */
class Digitalus_Content_Filter extends Digitalus_Abstract
{
    /**
     * Attributes for the specific navigation helper
     * @var array
     */
    protected $_attribs = array();

    /**
     * Attributes that must be booleanised before processing them
     * @var array
     */
    protected $_booleanValues = array();

    /**
     * The Html tag
     * @var string
     */
    public $tag;

    /**
     * Digitalus_Page object
     * @var array
     */
    public $page;

    public function filter($content)
    {
        $this->page = Digitalus_Builder::getPage();

        $pattern = '(\<' . $this->tag . '(/?[^\>]+)\>)';
        $content = preg_replace_callback($pattern, array($this, '_callback'), $content);
        return $content;
    }

    public function getAttributes($element)
    {
        $xml = @simplexml_load_string($element);
        if ($xml) {
            foreach ($xml->attributes() as $key => $value) {
                $attr[$key] = (string)$value;
            }
            return $attr;
        }
        return null;
    }

    /**
     * Sets multiple attributes at a time
     *
     * @param  array $attribs
     * @return void
     */
    protected function _setAttribs($attribs)
    {
        if (is_array($attribs)) {
            foreach ($attribs as $key => $value) {
                $this->_setAttrib($key, $value);
            }
        }
    }

    /**
     * Sets one attribute
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    protected function _setAttrib($key, $value)
    {
        if (key_exists($key, $this->_attribs)) {
            if (in_array($key, $this->_booleanValues)) {
                // needed to convert "true" or "false" strings into boolean values
                $this->_attribs[$key] = Digitalus_Toolbox_String::booleanise($key);
            } else {
                $this->_attribs[$key] = (string)$value;
            }
        }
    }

    /**
     * Returns all attributes
     *
     * @return array
     */
    protected function _getAttribs()
    {
        return $this->_attribs;
    }

    /**
     * Returns the value for one specific attribute or false if it doesn't exist
     *
     * @param  string $key
     * @return string|false
     */
    protected function _getAttrib($key)
    {
        if (key_exists($key, $this->_attribs)) {
            return $this->_attribs[$key];
        }
        return null;
    }

  protected function _cleanUpAttribs()
  {
    foreach ($this->_getAttribs() as $key => $value) {
      if (is_null($value)) {
        unset($this->_attribs[$key]);
      }
    }
  }


}