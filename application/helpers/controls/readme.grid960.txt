//start by building the grid

//create the header wrapper
$header = $grid->addUnit(16);

//add the left and right header boxes
$left = $grid->startRow(12);
$right = $grid->endRow(4);

//split the right unit with a top and bottom unit
$top = $grid->addUnit(4, $right);
$bottom = $grid->addUnit(4, $right);

// then add content
$grid->populate($left, "left unit");
$grid->populate($top, "right top unit");
$grid->populate($bottom, "right bottom unit");

//render the grid
echo $grid->render();

//this renders:
/*
 
<div class="container_16">
	<div class="grid_16">
		<div class="grid_12 alpha">
			<div>left unit</div>
		</div>
		<div class="grid_4 omega">
			<div class="grid_4">
				<div>right top unit</div>
			</div>
    		<div class="grid_4">
            	<div>right bottom unit</div>
            </div>
    	</div>
    </div>
</div>