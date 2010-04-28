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
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Form
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Partial.php 729 2010-04-19 20:11:57Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Digitalus_Form_Element_Xml
 */
require_once 'Digitalus/Form/Element/Xml.php';

/**
 * Form Element Partial
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Digitalus_Form_Element_Partial extends Digitalus_Form_Element_Xml
{
    public $partial;

    /**
     * Initialize object; inherited from Zend_Form_Element_Textarea
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setDecorators(array(array("ViewScript", array(
            'viewScript' => $this->partial,
            'class'      => 'partial'
        ))));
    }

    /**
     * Sets the partial script
     *
     * @return void
     */
    public function setPartial($script)
    {
        $this->partial = $script;
    }
}