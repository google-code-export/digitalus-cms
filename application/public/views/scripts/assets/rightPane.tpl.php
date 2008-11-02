<?php if($this->content->featured_content != ''){?>
<div class="feature_box">
<?php 
echo stripslashes($this->content->featured_content);
?>
</div>
<?php } ?>
