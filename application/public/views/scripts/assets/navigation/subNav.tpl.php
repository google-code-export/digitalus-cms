<?php
$m = new Menus();
$mi = new MenuItems();

$menu = $this->section;

$currMenu = $m->fetchRow("name='{$menu}'");

$menuItems = $mi->fetchAll("menu_id={$currMenu->id}",'position');
foreach ($menuItems as $item){
	//set up the seo friendly names
	$lMenu=strtolower($this->AddHyphen($currMenu->name));
	$lPage = strtolower($this->AddHyphen($item->name));

	//selected nav
	if(strtolower($this->selectedNav) == strtolower($item->name)){
		$selected = 'selectedNav';
	}elseif($this->selectedNav == 'false'){ //select the first item
		$selected = 'selectedNav';
		$this->selectedNav = '';
	}else{
		$selected = '';
	}
	
	//use label if it exists
	if($item->label != ''){
		$label = ucwords($item->label);	
	}else{
		$label = ucwords($item->name);
	}
	
    $newItem="<a href='{$this->baseUrl}{$lMenu}/{$lPage}' id='t{$item->link_type}id{$item->link}' class='only {$selected}'>{$label}</a>";
	//check to see if the menu has a sub menu
    if($item->sub_menu_id > 0){
    	$sItems = $mi->fetchAll("menu_id={$item->sub_menu_id}",'position');
    	foreach ($sItems as $sub_menu_item){
			$lSubMenu = strtolower($this->AddHyphen($item->name));
    		$lsPage=strtolower($this->AddHyphen($sub_menu_item->name));
			
			//use label if it exists
			if($sub_menu_item->label != ''){
				$label = ucwords($sub_menu_item->label);	
			}else{
				$label = ucwords($sub_menu_item->name);
			}
	
		    $newItem.="<a href='{$this->baseUrl}{$lMenu}/{$lSubMenu}/{$lsPage}' id='t{$sub_menu_item->link_type}id{$sub_menu_item->link}' class='thirdLevel'>". ucwords($sub_menu_item->name) . "</a>";
		}
    }
    $selected = '';
    $items[] = $newItem;
}
if(is_array($items)){
    echo $this->HtmlList($items, false, array('id'=>'subnav'));
}else{
	//put an empty ul to block out the space
?>
<ul id='subnav'></ul>
<?php
}

foreach ($menuItems as $item){
    $newItem="<a href='{$this->baseUrl}{$this->section}/{$currMenu->name}/{$item->link}' id='t{$item->link_type}id{$item->link}' class='only'>{$item->name}</a>";
    if($item->sub_menu_id > 0){
    	$sItems = $mi->fetchAll("sub_menu_id={$item->sub_menu_id}");
    }
    
    $items[] = $newItem;
}

