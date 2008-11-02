<h1><?php echo $this->currFolder->name;?></h1>

<h2><?php echo $this->indexPage->title;?></h2>
<?php
//load the page index
foreach ($this->contentItems as $item){
$this->pageIndex[] = "<a class='indexLink' href='{$this->baseUrl}index/render/t/1/page/{$item->id}'>{$item->title}</a>";
}
?>

<?php echo $this->indexPage->content;