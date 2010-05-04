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
 * @author      LowTower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Form
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Zend_Form_Decorator_Abstract
 */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Digitalus Error Form Decorator
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */
class Digitalus_Form_Decorator_MyErrors extends Zend_Form_Decorator_Abstract
{
    const FORMAT = '<div class="message">Field <span style="font-style: italic; font-weight: bold;">&laquo;%s&raquo;</span> not properly filled.<br />%s</div>';

    /**
     * Renders errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $errors = $element->getMessages();
        if (empty($errors)) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();

        $errors    = $view->formErrors($errors, $this->getOptions());
        if (empty($errors)) {
            return $content;
        }
        $label = 'captcha';
        if ('Zend_Form_Element_Captcha' != $element->getType()) {
            $label = htmlentities($element->getLabel());
        }
        $markup = sprintf(self::FORMAT, $label, $errors);

        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $markup;
            case self::PREPEND:
                return $markup . $separator . $content;
        }
    }
}