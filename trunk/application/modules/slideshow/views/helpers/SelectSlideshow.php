<?php
/**
 *
 * @author Forrest
 * @version
 */
require_once 'Zend/View/Helper/Abstract.php';
require_once './application/modules/slideshow/models/Show.php';

/**
 * selectSlideshow helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Digitalus_View_Helper_SelectSlideshow extends Zend_View_Helper_Abstract
{
    /**
     *
     */
    public function selectSlideshow ($name, $value)
    {
        $mdlShow = new Slideshow_Show();
        $shows = $mdlShow->getShows();
        if ($shows == null) {
            return $this->view->getTranslation('There are no slideshows to view!');
        } else {
            $options[] = $this->view->getTranslation('Select One');
            foreach ($shows as $show) {
                $options[$show->id] = $show->name;
            }
            return $this->view->formSelect($name, $value, null, $options);
        }
    }
}