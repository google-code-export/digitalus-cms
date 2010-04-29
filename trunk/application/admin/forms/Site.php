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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Page.php 701 2010-03-05 16:23:59Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin Site Form
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 * @uses        Model_Page
 */
class Admin_Form_Site extends Digitalus_Form
{
    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();

        $mdlSettings = new Model_SiteSettings();
        $settings    = $mdlSettings->toObject();

        /* *********************************************************************
         * SITE
         * ****************************************************************** */
        $siteName = $this->createElement('text', 'name', array(
            'label'         => $view->getTranslation('Site name'),
            'value'         => $settings->name,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $siteTitle = $this->createElement('text', 'title_separator', array(
            'label'         => $view->getTranslation('Title separator'),
            'value'         => $settings->title_separator,
            'belongsTo'     => 'setting',
            'attribs'       => array('class' => 'med'),
            'filters'       => array('StripTags'),
        ));
        $siteOnline = $this->createElement('checkbox', 'online', array(
            'label'         => $view->getTranslation('Site is online'),
            'value'         => intval($settings->online),
            'belongsTo'     => 'setting',
            'description'   => $view->getTranslation('Status'),
        ));
        $siteSubmit = $this->createElement('submit', 'site', array(
            'label'         => $view->getTranslation('Update General Site Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($siteName);
        $this->addElement($siteTitle);
        $this->addElement($siteOnline);
        $this->addElement($siteSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'name', 'title_separator', 'online', 'site'),
            'siteGroup',
            array('legend' => $view->getTranslation('General settings'))
        );

        /* *********************************************************************
         * CONTENT
         * ****************************************************************** */
        $homePage = $view->selectPage('home_page');
        $homePage->setOptions(array(
            'label'         => $view->getTranslation('Home Page'),
            'value'         => $settings->home_page,
            'belongsTo'     => 'setting',
        ));
        $pageNotFound = $view->selectPage('page_not_found');
        $pageNotFound->setOptions(array(
            'label'         => $view->getTranslation('404 Page'),
            'value'         => $settings->page_not_found,
            'belongsTo'     => 'setting',
        ));
        $offlinePage = $view->selectPage('offline_page');
        $offlinePage->setOptions(array(
            'label'         => $view->getTranslation('Site Offline Page'),
            'value'         => $settings->offline_page,
            'belongsTo'     => 'setting',
        ));
        $addMenuLinks = $this->createElement('checkbox', 'add_menu_links', array(
            'label'         => $view->getTranslation('Automatically add menu links'),
            'value'         => intval($settings->add_menu_links),
            'belongsTo'     => 'setting',
            'description'   => $view->getTranslation('Menu links'),
        ));
        $publishPages = $this->createElement('checkbox', 'publish_pages', array(
            'label'         => $view->getTranslation('Automatically publish pages on creation'),
            'value'         => intval($settings->publish_pages),
            'belongsTo'     => 'setting',
            'description'   => $view->getTranslation('Publish Pages'),
        ));
        $contentSubmit = $this->createElement('submit', 'content', array(
            'label'         => $view->getTranslation('Update Content Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($homePage);
        $this->addElement($pageNotFound);
        $this->addElement($offlinePage);
        $this->addElement($addMenuLinks);
        $this->addElement($publishPages);
        $this->addElement($contentSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'home_page', 'page_not_found', 'offline_page', 'add_menu_links', 'publish_pages', 'content'),
            'contentGroup',
            array('legend' => $view->getTranslation('Managing Content'))
        );

        /* *********************************************************************
         * DESIGN
         * ****************************************************************** */
        $designConfig = Zend_Registry::get('config')->template->default->public;
        $currentDesign = isset($settings->default_design) ? $settings->default_design : $designConfig->template . '_' . $designConfig->page;
        $defaultDesign = $view->selectDesign('default_design', $currentDesign);
        $defaultDesign->setOptions(array(
            'label'         => $view->getTranslation('Default design'),
            'value'         => $currentDesign,
            'belongsTo'     => 'setting',
        ));
        $designSubmit = $this->createElement('submit', 'design', array(
            'label'         => $view->getTranslation('Update Design Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($defaultDesign);
        $this->addElement($designSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'default_design', 'design'),
            'designGroup',
            array('legend' => $view->getTranslation('Design'))
        );

        /* *********************************************************************
         * META DATA
         * ****************************************************************** */
        $xmlDeclaration = $view->radioXmlDeclaration('xml_declaration', $settings->xml_declaration);
        $xmlDeclaration->setOptions(array(
            'label'         => $view->getTranslation('XML declaration'),
            'value'         => $settings->xml_declaration,
            'belongsTo'     => 'setting',
            'description'   => $view->getTranslation('XML declaration'),
        ));
        $docType = $view->selectDoctype('doc_type', $settings->doc_type);
        $docType->setOptions(array(
            'label'         => $view->getTranslation('Doc Type'),
            'value'         => $settings->doc_type,
            'belongsTo'     => 'setting',
        ));
        $defaultCharset = $this->createElement('text', 'default_charset', array(
            'label'         => $view->getTranslation('Default charset'),
            'value'         => $settings->default_charset,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $defaultTimezone = $view->selectTimezone('default_timezone', $settings->default_timezone);
        $defaultTimezone->setOptions(array(
            'label'         => $view->getTranslation('Default timezone'),
            'value'         => $settings->default_timezone,
            'belongsTo'     => 'setting',
            'validators'    => array('Timezone'),
        ));
        $metaSubmit = $this->createElement('submit', 'meta', array(
            'label'         => $view->getTranslation('Update Page Meta Data'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($xmlDeclaration);
        $this->addElement($docType);
        $this->addElement($defaultCharset);
        $this->addElement($defaultTimezone);
        $this->addElement($metaSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'xml_declaration', 'doc_type', 'default_charset', 'default_timezone', 'meta'),
            'metaGroup',
            array('legend' => $view->getTranslation('Meta Data'))
        );

        /* *********************************************************************
         * INTERNATIONALISATION
         * ****************************************************************** */
        $defaultLanguage = $view->selectLanguage('default_language', $settings->default_language);
        $defaultLanguage->setOptions(array(
            'label'         => $view->getTranslation('Default language'),
            'value'         => $settings->default_language,
            'belongsTo'     => 'setting',
        ));
        $adminLanguageValue = $view->getAdminLanguage();
        $adminLanguage = $view->selectLanguage('admin_language', $settings->admin_language);
        $adminLanguage->setOptions(array(
            'label'         => $view->getTranslation('Admin language'),
            'value'         => $adminLanguageValue,
            'belongsTo'     => 'setting',
        ));
        $internationalSubmit = $this->createElement('submit', 'language', array(
            'label'         => $view->getTranslation('Update Internationalization Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($defaultLanguage);
        $this->addElement($adminLanguage);
        $this->addElement($internationalSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'default_language', 'admin_language', 'language'),
            'internationalGroup',
            array('legend' => $view->getTranslation('Internationalization'))
        );

        /* *********************************************************************
         * EMAIL
         * ****************************************************************** */
        $defaultEmail = $this->createElement('text', 'default_email', array(
            'label'         => $view->getTranslation('Default email address'),
            'value'         => $settings->default_email,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim'),
            'validators'    => array('EmailAddress'),
            'errorMessages' => array('A valid email address is required'),
        ));
        $defaultEmailSender = $this->createElement('text', 'default_email_sender', array(
            'label'         => $view->getTranslation('Default sender'),
            'value'         => $settings->default_email_sender,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $useSmtpMail = $this->createElement('checkbox', 'use_smtp_mail', array(
            'label'         => $view->getTranslation('Use SMTP Transport'),
            'value'         => intval($settings->use_smtp_mail),
            'belongsTo'     => 'setting',
            'description'   => $view->getTranslation('Transport'),
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $smtpHost = $this->createElement('text', 'smtp_host', array(
            'label'         => $view->getTranslation('SMTP host'),
            'value'         => $settings->smtp_host,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $smtpUsername = $this->createElement('text', 'smtp_username', array(
            'label'         => $view->getTranslation('SMTP username'),
            'value'         => $settings->smtp_username,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $smtpPassword = $this->createElement('password', 'smtp_password', array(
            'label'         => $view->getTranslation('SMTP password'),
            'value'         => $settings->smtp_password,
            'belongsTo'     => 'setting',
            'filters'       => array('StringTrim'),
        ));
        $mailTest = $this->createElement('AnyMarkup', 'mail_test', array(
            'value'         => $view->link($view->getTranslation('Send test message'), '/admin/site/mail-test', 'email_go.png'),
            'decorators'    => $this->getStandardDecorator('text'),
        ));
        $mailSubmit = $this->createElement('submit', 'mail', array(
            'label'         => $view->getTranslation('Update Mail Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($defaultEmail)
             ->addElement($defaultEmailSender)
             ->addElement($useSmtpMail)
             ->addElement($smtpHost)
             ->addElement($smtpUsername)
             ->addElement($smtpPassword)
             ->addElement($mailTest)
             ->addElement($mailSubmit);
        $group = $this->addDisplayGroup(
            array('form_instance', 'default_email', 'default_email_sender', 'use_smtp_mail', 'smtp_host', 'smtp_username', 'smtp_password', 'mail_test', 'mail'),
            'mailGroup',
            array('legend' => $view->getTranslation('Mail Settings'))
        );

        /* *********************************************************************
         * GOOGLE INTEGRATION
         * ****************************************************************** */
        $googleVerify = $this->createElement('textarea', 'google_verify', array(
            'label'         => $view->getTranslation('Verification code'),
            'value'         => $settings->google_verify,
            'belongsTo'     => 'setting',
            'attribs'       => array('class' => 'med'),
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $googleTracking = $this->createElement('textarea', 'google_tracking', array(
            'label'         => $view->getTranslation('Tracking code'),
            'value'         => $settings->google_tracking,
            'belongsTo'     => 'setting',
            'attribs'       => array('class' => 'med'),
            'filters'       => array('StringTrim', 'StripTags'),
        ));
        $googleSubmit = $this->createElement('submit', 'google', array(
            'label'         => $view->getTranslation('Update Google Settings'),
            'attribs'       => array('class' => 'submit'),
        ));
        $this->addElement($googleVerify);
        $this->addElement($googleTracking);
        $this->addElement($googleSubmit);
        $this->addDisplayGroup(
            array('form_instance', 'google_verify', 'google_tracking', 'google'),
            'googleGroup',
            array('legend' => $view->getTranslation('Google Integration'))
        );
    }
}