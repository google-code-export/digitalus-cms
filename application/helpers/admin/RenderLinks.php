<?php
class DSF_View_Helper_Admin_RenderLinks
{

	/**
	 * comments
	 */
	public function RenderLinks($links, $class, $prependText = null, $appendText = null, $separator = ' | '){
        if(is_array($links) && count($links) > 0){
            foreach ($links as $label => $link) {
                $linkClass = strtolower($label);
                $linkClass = str_replace(' ', '_', $linkClass);
            	$hyperlinks[] = "<a href='{$link}' class='{$linkClass}'>{$label}</a>";
            }
            return "<p class='{$class}'>" . $prependText . implode($separator, $hyperlinks) . $appendText . "</p>";
        }
	}
}