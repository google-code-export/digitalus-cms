<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectLanguage helper
 *
 * @uses viewHelper DSF_View_Helper_Internationalization
 */
class DSF_View_Helper_Internationalization_LanguageForm {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *  this helper renders a language selector
     *  it also processes the selected language
     *  it must be rendered above the content in order for the current
     *  content to reflect the language selection
     */
    public function languageForm()
    {
        //process form if this is a post back
        if (DSF_Filter_Post::has('setLang')) {
            DSF_Language::setLanguage($_POST['language']);
            // @todo: this needs to redirect so it loads the whole page in the new language
        }

        $currentLanguage = DSF_Language::getLanguage();

        $languageSelector = $this->view->selectLanguage('language',$currentLanguage);
        $xhtml  = '<form action="' . $this->view->ModuleAction() . '" method="post">';
        $xhtml .= '<p>' . $languageSelector . '</p>';
        $xhtml .= '<p>' . $this->view->formSubmit('setLang', $this->view->GetTranslation('Set Language')) . '</p>';
        $xhtml .= '</form>';
        return $xhtml;
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}