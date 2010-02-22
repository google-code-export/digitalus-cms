<?php
/**
 * LanguageForm helper
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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * LanguageForm helper
 *
 * @author      Forrest Lyman
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        viewHelper  Digitalus_View_Helper_GetTranslation
 * @uses        viewHelper  Digitalus_View_Helper_SelectLanguage
 * @uses        viewHelper  Digitalus_View_Helper_ModuleAction
 */
class Digitalus_View_Helper_Internationalization_LanguageForm extends Zend_View_Helper_Abstract
{
    /**
     *  this helper renders a language selector
     *  it also processes the selected language
     *  it must be rendered above the content in order for the current
     *  content to reflect the language selection
     */
    public function languageForm()
    {
        //process form if this is a post back
        if (Digitalus_Filter_Post::has('setLang')) {
            Digitalus_Language::setLanguage($_POST['language']);
            // @todo: this needs to redirect so it loads the whole page in the new language
        }

        $currentLanguage = Digitalus_Language::getLanguage();

        $languageSelector = $this->view->selectLanguage('language', $currentLanguage);
        $xhtml  = '<form action="' . $this->view->moduleAction() . '" method="post">';
        $xhtml .= '<p>' . $languageSelector . '</p>';
        $xhtml .= '<p>' . $this->view->formSubmit('setLang', $this->view->getTranslation('Set Language')) . '</p>';
        $xhtml .= '</form>';
        return $xhtml;
    }
}