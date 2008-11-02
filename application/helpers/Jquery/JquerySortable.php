

<?php
class DSF_View_Helper_Jquery_JquerySortable
{

	/**
	 * comments
	 */
	public function JquerySortable($selector, $sortableClass = 'sortableItem'){
        $xhtml = "
        		$('$selector').Sortable(
        			{
        				accept : 		'$sortableClass',
        				helperclass : 	'sorthelper',
        				activeclass : 	'sortableactive',
        				hoverclass : 	'sortablehover',
        				opacity: 		0.8,
        				fx:				200,
        				axis:			'vertically',
        				opacity:		0.4,
        				revert:			true,
        				handle: 		'a.handle'
        			}
        		);";
        
		return $xhtml;
	}
}