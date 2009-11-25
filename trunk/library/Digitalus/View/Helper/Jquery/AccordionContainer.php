<?php
/**
 * AccordionContainer helper
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
 * @category    Digitalus
 * @package     Digitalus_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/**
 * AccordionContainer helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper Digitalus_View_Helper_GetTranslation
 */
class Digitalus_View_Helper_Jquery_AccordionContainer extends ZendX_JQuery_View_Helper_AccordionContainer
{
    protected $_panes = array();

    protected $_elementHtmlTemplate = '<h3><a href="#">%s</a><h3><div>%s</div>';

    /**
     * Render Accordion with the currently registered elements.
     *
     * If no arguments are given, the accordion object is returned so that
     * chaining the {@link addPane()} function allows to register new elements
     * for an accordion.
     *
     * @link    http://docs.jquery.com/UI/Accordion
     * @param   string  $id
     * @param   array   $params
     * @param   array   $attribs
     * @return  string|ZendX_JQuery_View_Helper_AccordionContainer
     */
    public function accordionContainer($id=null, array $params=array(), array $attribs=array())
    {
        if (0 === func_num_args()) {
            return $this;
        }
        if (!isset($attribs['id'])) {
            $attribs['id'] = $id;
        }
        if (isset($this->_panes[$id])) {
            $html = "";
            foreach ($this->_panes[$id] AS $element) {
                $html .= sprintf($this->_elementHtmlTemplate, $element['name'], $element['content']). PHP_EOL;
            }
            if (count($params) > 0) {
                $params = ZendX_JQuery::encodeJson($params);
            } else {
                $params = "{}";
            }
            $js = sprintf('%s("#%s").accordion(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
            );
            $this->jquery->addOnLoad($js);

            $html = '<div'
                  . $this->_htmlAttribs($attribs)
                  . '>'.PHP_EOL
                  . $html
                  . '</div>'.PHP_EOL;
            return $html;
            unset($this->_panes[$id]);
        }
        return '';
    }

    /**
     * Add Accordion Pane for the Accordion-Id
     *
     * @param  string $id
     * @param  string $name
     * @param  string $content
     * @return ZendX_JQuery_View_Helper_AccordionContainer
     */
    public function addPane($id, $name, $content, array $options=array())
    {
        die('here');
        if (!isset($this->_panes[$id])) {
            $this->_panes[$id] = array();
        }
        if (strlen($name) == 0 && isset($options['title'])) {
            $name = $options['title'];
        }
        $this->_panes[$id][] = array('name' => $name, 'content' => $content, 'options' => $options);
        return $this;
    }
}