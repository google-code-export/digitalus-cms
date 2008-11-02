<?php
class DSF_View_Helper_Cms_FckEditor
{

	/**
	 * i know it is not well liked to output this here, but for integration purposes it makes sense
	 */
	public function FckEditor($instance = 'content', $value='Enter text here', $height= 600, $width=600, $fullToolbar = true){
		$view=$this->view;
		include('DSF/editor/fckeditor.php') ;
        ?>
        <script>
        function FCKeditor_OnComplete( editorInstance )
        {
        }
		</script>
        
        <?php
        $sBasePath = '/public/scripts/fckeditor/' ;
        
        $oFCKeditor = new FCKeditor($instance) ;
        $oFCKeditor->BasePath = $sBasePath ;
        $oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/DSF/' ;
        $oFCKeditor->Width		= $width ;
        $oFCKeditor->Height		= $height ;
        if($fullToolbar){
            $oFCKeditor->ToolbarSet	= 'DSF' ;
        }else{
            $oFCKeditor->ToolbarSet	= 'Basic' ;
        }
        $oFCKeditor->Value = $value ;
        
        $oFCKeditor->Create() ;

	}
	
    /**
     * Set this->view object
     *
     * @param  Zend_this->view_Interface $this->view
     * @return Zend_this->view_Helper_DeclareVars
     */
    public function setview(Zend_view_Interface $view)
    {
        $this->view = $view;
        return $this;
    }
}