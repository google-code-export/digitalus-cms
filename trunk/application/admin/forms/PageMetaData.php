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
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */

/**
 * @see Digitalus_Form
 */
require_once 'Digitalus/Form.php';

/**
 * Admin Page Metadata Form
 *
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @category    Digitalus CMS
 * @package     Digitalus_CMS_Admin
 * @version     $Id:$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10.0
 */
class Admin_Form_PageMetaData extends Digitalus_Form
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

        $pageId = $this->createElement('hidden', 'page_id');
        $pageId->addFilter('int');

        $pageTitle = $this->createElement('text', 'page_title');
        $pageTitle->setLabel($view->getTranslation('Page Title') . ':')
                  ->addFilter('StripTags')
                  ->setAttrib('class', 'med');

        $filename = $this->createElement('text', 'filename');
        $filename->setLabel($view->getTranslation('Filename') . ':')
                 ->addFilter('StripTags')
                 ->setAttrib('class', 'med');

        $metaDescription = $this->createElement('textarea', 'meta_description');
        $metaDescription->setLabel($view->getTranslation('Meta Description') . ':')
                        ->addFilter('StripTags')
                        ->setAttrib('class', 'med_short');


        $metaKeywords = $this->createElement('textarea', 'keywords');
        $metaKeywords->setLabel($view->getTranslation('Meta Keywords') . ':')
                     ->addFilter('StripTags')
                     ->setAttrib('class', 'med_short');

        $searchTags = $this->createElement('textarea', 'search_tags');
        $searchTags->setLabel($view->getTranslation('Search Tags') . ':')
                   ->addFilter('StripTags')
                   ->setAttrib('class', 'med_short');

        $submit = $this->createElement('submit', 'update');
        $submit->setLabel($view->getTranslation('Update Meta Data'));

        // Add elements to form:
        $this->addElement($pageId)
             ->addElement($pageTitle)
             ->addElement($filename)
             ->addElement($metaDescription)
             ->addElement($metaKeywords)
             ->addElement($searchTags)
             ->addElement($submit);

        $this->addDisplayGroup(
            array('form_instance', 'id', 'page_title', 'filename', 'meta_description', 'keywords', 'search_tags', 'update'),
            'updatePageMetaDataGroup',
            array('legend' => $view->getTranslation('Edit Meta Data'))
        );
    }
}