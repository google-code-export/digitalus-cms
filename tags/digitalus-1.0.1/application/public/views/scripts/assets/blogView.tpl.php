<h1><?php echo $this->currFolder->name;?></h1>

<?php
foreach ($this->contentItems as $item){
?>
<h2><?php echo $item->title;?></h2>
<?php echo $this->TruncateText($item->content);
?>
<a class='readMoreLink' href='<?php echo "{$this->baseUrl}index/render/t/2/page/{$item->id}";?>'>Read&nbsp;More</a>
<?php
}
