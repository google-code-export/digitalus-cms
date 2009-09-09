<?php
/**
 * this class inherits all of the base properties from Digitalus_Content_Item_Abstract
 * these map to the columns in the pages table.
 *
 * if you add public properties to this class they will be managed by Digitalus_Content_Item_Abstract as content nodes
 *
 */
class Digitalus_Content_Item_Page extends Digitalus_Content_Item_Abstract
{
    protected $_namespace = 'content';
    public $headline;
    public $teaser;
    public $content;
}