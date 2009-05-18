<?php
class DSF_View_Helper_General_Pagination
{
    /**
     * creates links to paginate lists
     */
    public function Pagination($baseUrl, $currentPage, $pages)
    {
        if ($pages > 1) {
           //setup the direct links to each page
           $directLinks = '';
            for ($i=1;$i <= $pages; $i++) {
                if ($i == $currentPage) {
                    $class = 'selected';
                } else {
                    $class = '';
                }
                $directLinks .= "<a href='{$baseUrl}/page/{$i}' class='{$class}'>{$i}</a>";
            }

            //first page
            $xhtml = "<a href='{$baseUrl}/page/1'>&lt;&lt; " . $this->view->getTranslation('First') . '</a>';

            //previous page
            if ($currentPage > 1) {
                $previous = $currentPage - 1;
                $xhtml .= "<a href='{$baseUrl}/page/{$previous}'>&lt; " . $this->view->getTranslation('Previous') . '</a>';
            }

            //direct links
            $xhtml .= $directLinks;

            //next page
            if ($currentPage < $pages) {
                $next = $currentPage + 1;
                $xhtml .= "<a href='{$baseUrl}/page/{$next}'>" . $this->view->getTranslation('Next') . ' &gt;</a>';
            }

            //last page
            $xhtml .= "<a href='{$baseUrl}/page/{$pages}'>" . $this->view->getTranslation('Last') .  '&gt;&gt;</a>';

            return $xhtml;
        }
    }

}