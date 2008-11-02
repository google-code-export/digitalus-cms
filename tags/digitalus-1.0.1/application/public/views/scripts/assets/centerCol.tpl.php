<script>
	  $("h2").corner('round 5px');
      $(".message_box").bind("click",function(){
           $(this).fadeOut("slow");
      }
      );
</script>
<?php echo $this->content;?>

