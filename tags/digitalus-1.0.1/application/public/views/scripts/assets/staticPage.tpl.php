<div id="center_content_wrapper">

<div id="breadcrumbs"><?php echo $this->breadcrumbs;?></div>

<?php if($this->content->friendly_name != ''){?>
<h1><?php echo $this->content->friendly_name;?></h1>
<?php }elseif(strtolower($this->content->title) != 'index'){?>
<h1><?php echo $this->content->title;?></h1>
<?php }else{?>
<h1><?php echo $this->defaultH1;?></h1>
<?php }
echo stripslashes($this->content->content);

if($this->content->module_name != '0'){echo $this->RenderModule($this->content->module_name);}
?>
</div>