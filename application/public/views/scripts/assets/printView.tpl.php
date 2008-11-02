<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->escape($this->title); ?></title>

<script>
window.print();
</script>

</head>

<body>

<h1><?php echo $this->content->title;?></h1>
<?php echo stripslashes($this->content->content);?>