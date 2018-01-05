<?php
session_start();



?>

<?php

if(isset($_POST['myForm'])){
	$idproduct = $_POST('myForm');
	$wasFound = false;
	$i = 0;


	if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1){

		$_SESSION["cart_array"] = array(1 => array("item_id" => $idproduct, "quantity" => 1));
	}else{

		foreach($_SESSION["cart_array"] as $each_item){
			$i++;
			while(list($key, $value) = each($each_item)){
				if($key == "item_id" && value == $myForm){
					array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $myForm, "quantity" => $each_item['quantity'] + 1)));
					$wasFound = true;
				}
			}
		}
		if($wasFound == false){
			array_push($_SESSION["cart_array"], array("item_id" => $myForm, "quantity" => 1));
		}
	}
}



?>

<?php

$cart = "";
if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1){
	$cart = "<p>Yor cart is emoty</p>";
}else{
	$i = 0;
	foreach($_SESSION["cart_array"] as $each_item){
		$i++;
		$cart .="<p>Item $i</p>";
		while(list($key, $value) = each($each_item)){
			$cart .= "$key: $value</br>";
		}
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Customer</title>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

	<div align="center" id="mainWrapper">	
	<?php include_once("../template_header.php");?>
	<div id="pageContent"><br/>
		<div align="left" style="margin-left: 24px;">
			<h2>Login Successful!</h2>
			<br/>
			<form action="index.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
			<table border="1">
			<?php echo $cart; ?>
			
			
			
	<?php include_once("../template_footer.php");?>
	</div>
</body>
</html>