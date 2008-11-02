<script>
	  $("h2").corner('round 5px');

      $(".menu li a").bind("click", function(){
          var txt = $(this).text();
          var $a = $(this);
          $a.text('Loading...');
		   $('#centerCol').load($(this).attr('href'), false, function(){
		     $a.text(txt);
		   });
		return false; 
      });
</script>

<div id="leftCol" class="boxBelow">	
    <?php echo $this->left;?>
</div>
<div id="centerCol" class="boxBelow">
<?php echo $this->center;?>
</div>
<div id="rightCol" class="boxBelow">
    <?php echo $this->right;?>
</div>
<br class="clear"/>
