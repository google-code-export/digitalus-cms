<form action="/<?php echo $this->ctl . '/delete';?>" method="POST">
<p>Are you sure you want to permanently delete this?</p>
<p>There is no undo!</p>
<p>
<?php 
    echo $this->formHidden('id',$this->id);
    echo $this->formSubmit('confirm','Yes');
    echo $this->formSubmit('confirm','No');
?>
</p>
</form>