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
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */

/**
 * @see Zend_Form_Element_Textarea
 */
require_once 'Zend/Form/Element/Textarea.php';

/**
 * Markitup Textarea Element
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.9.0
 */
class Digitalus_Form_Element_Markitup extends Zend_Form_Element_Textarea
{
    /**
     * Initialize object; inherited from Zend_Form_Element_Textarea
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->setAttrib('class', 'markItUp')
             ->setDecorators(array('Composite'))
             ->addFilter('StripSlashes');
    }
}