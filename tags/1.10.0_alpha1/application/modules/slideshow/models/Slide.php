<?php
class Slideshow_Slide extends Model_Page
{
    protected $_namespace = 'slideshow_slide';

    public function getSlides($showId)
    {
        $showId = intval($showId);
        $slides = $this->getChildren($showId, null, 'position');
        if ($slides != null) {
            foreach ($slides as $slide) {
                $slideArray[] = $this->openSlide($slide->id);
            }
            return $slideArray;
        } else {
            return null;
        }

    }

    public function getSlideByShow($show, $index = 1)
    {
        //0 is the first element, but we use 1 on the front end
        $index--;
        $select = $this->select();
        $select->order('position');
        $select->limit(1, $index);
        $slide = $this->fetchAll($select)->current();
        if ($slide) {
            return $this->openSlide($slide->id);
        } else {
            return null;
        }

    }

    public function getShowBySlide($slideId)
    {
        $row = $this->find($slideId)->current();
        if ($row) {
            return $row->parent_id;
        } else {
            return null;
        }
    }

    public function countSlidesInShow($show)
    {
        $select = $this->select();
        $select->from($this->_name, 'COUNT(id) as records');
        $select->where('parent_id = ?', $show);
        $result = $this->fetchRow($select);
        return $result->records;
    }

    public function openSlide($slideId)
    {
        $slide = $this->find($slideId)->current();
        if ($slide) {
            $mdlContentNode = new Model_PageNode();
            $content = $mdlContentNode->fetchContentArray($slide->id, null, null, $this->getDefaultLanguage());
            $objSlide = new stdClass();
            $objSlide->id = $slide->id;
            $objSlide->title = $slide->name;
            $objSlide->dateCreated = $slide->create_date;
            $objSlide->showId = $slide->parent_id;

            if (isset($content['preview_path'])) {
                $objSlide->previewPath = $content['preview_path'];
            } else {
                $objSlide->previewPath = null;
            }

            if (isset($content['image_path'])) {
                $objSlide->imagePath = $content['image_path'];
            } else {
                $objSlide->imagePath = null;
            }
            if (isset($content['caption'])) {
                $objSlide->caption = $content['caption'];
            } else {
                $objSlide->caption = null;
            }
            return $objSlide;
        } else {
            return null;
        }
    }

    public function createSlide($showId, $title)
    {
        return $this->createPage($title, $showId);
    }

    public function updateSlide($slideId, $title, $caption, $previewPath = null, $imagePath = null)
    {
        $data = array(
            'page_id'   => $slideId,
            'name'      => $title,
            'caption'   => $caption
        );

        if ($previewPath != null) {
            $data['preview_path'] = $previewPath;
        }

        if ($imagePath != null) {
            $data['image_path'] = $imagePath;
        }
        $this->edit($data);
    }

    public function sortSlides($ids)
    {
        if (is_array($ids)) {
            for ($i = 0; $i <= (count($ids) - 1); $i++) {
                $slide = $this->find($ids[$i])->current();
                $slide->position = $i;
                $slide->save();
            }
        }
    }

}