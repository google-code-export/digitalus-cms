<?php
require_once './application/modules/search/adapter/Abstract.php';
class Search_Adapter_Content extends Search_Adapter_Abstract
{
    public function __construct()
    {
        $mdlPage = new Model_Page();
        $mdlContentNode = new Model_ContentNode();
        $select = $mdlPage->select();
        $select->where("namespace = 'content'");
        $pages = $mdlPage->fetchAll($select);

        if ($pages->count() > 0) {
            foreach ($pages as $page) {
                $contentNodes = $mdlContentNode->fetchContentObject($page->id);
                if (isset($contentNodes->content)) {
                    //if the page does not have content it doesnt belong in the index (eg blocks)
                    $title = $mdlPage->getPageTitle($page->id);
                    $link = DSF_Toolbox_Page::getUrl($page);
                    $link = strtolower(DSF_Toolbox_String::addHyphens($link));

                    $contentNodes = $mdlContentNode->fetchContentObject($page->id);
                    if (isset($contentNodes->teaser)) {
                        $teaser = $contentNodes->teaser;
                    } else {
                        $teaser = DSF_Toolbox_String::truncateText($contentNodes->content);
                    }
                    $content = $contentNodes->content;
                    $this->_addPage($link, $title, $teaser, $content);
                }
            }
        }
    }
}
?>
