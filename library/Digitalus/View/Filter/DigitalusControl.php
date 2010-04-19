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
 * @category    Digitalus
 * @package     Digitalus
 * @subpackage  Digitalus_View
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Digitalus_Content_Filter
 */
require_once 'Digitalus/Content/Filter.php';

/**
 * DigitalusControl
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 * @uses        Digitalus_Content_Filter
 */
class Zend_View_Filter_DigitalusControl extends Digitalus_Content_Filter
{
    public $tag = 'digitalusControl';

    protected function _callback($matches)
    {
        $attribs = $this->getAttributes($matches[0]);
        if (is_array($attribs)) {
            $content = $this->page->getContent();
            if (isset($content[$attribs['id']])) {
                $controlContent = $content[$attribs['id']];
                switch ($attribs['type']) {
                    case 'fckeditor':
                    case 'markitup':
                    case 'tinymce':
                    case 'wymeditor':
                    case 'wysiwyg':
                        $xhtml = '<div id="' . $attribs['id'] . '_wrapper">' . $controlContent . '</div>';
                        break;
                    case 'text':
                    case 'textarea':
                    default:
                        $xhtml = $controlContent;
                        break;
                    case 'moduleSelector':
                        $xhtml = $this->view->renderModule($controlContent);
                        break;
                    case 'image':
                        $config = Zend_Registry::get('config');
                        $mediaFolder = $config->filepath->media;
                        $xhtml = $this->view->renderImage($controlContent);
                        break;
                }
                if (isset($attribs['tag']) && !empty($xhtml)) {
                    return '<' . $attribs['tag'] . '>' . $xhtml . '</' . $attribs['tag'] . '>';
                } else {
                    return $xhtml;
                }
            }
        }
        return null;
    }
}