<?php
class DSF_View_Helper_Content_RenderBlock
{
    public function RenderBlock($path, $key = 'content')
    {
        $page = new Page();
        $content = $page->getContent($path);

        if ($content && isset($content[$key])) {
            return $content[$key];
        } else {
            return null;
        }
    }
}