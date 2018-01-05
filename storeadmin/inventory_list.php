<?php
session_start();
if(!isset($_SESSION["manager"])){
	header("location: admin_login.php");
	exit();
}
$managerID = preg_replace('#[^0-9]#i','', $_SESSION["id"]);
$manager = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["manager"]);
$password = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["password"]);

include "../storescripts/connect_to_mysql.php";
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");

$existCount = mysql_num_rows($sql);
if($existCount == 0){
	echo "Your login session data is not on record in the database";
	exit();
}
//Be sure to check that this manager SESSION value is in fact in the database
?>

<?php

if(isset($_GET['deleteid'])){
	$iddelete = $_GET['deleteid'];
    mysql_query("DELETE FROM `comments` WHERE product_idproduct='$iddelete'") or die(mysql_error());
	$sql = mysql_query("DELETE FROM product WHERE idproduct='$iddelete' LIMIT 1") or die(mysql_error());
	
	
}


?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>


<?php
//Parsing data.
if(isset($_POST['Name'])){

	$Name = mysql_real_escape_string($_POST['Name']);
	$Price = mysql_real_escape_string($_POST['Price']);
	$Available = mysql_real_escape_string($_POST['details']);

	$sql = mysql_query("SELECT idproduct FROM product WHERE Name='$Name' LIMIT 1");
	$productMatch = mysql_num_rows($sql);
	if($productMatch > 0){
		echo 'Sorry, that product name already exists! <a href="inventory_list.php">Try again!</a>';
		exit();
	}

	$sql = mysql_query("INSERT INTO product(Name, Available, Price, Rating, TotalRaters) VALUES ('$Name', '$Available', '$Price',  '0', '0')") or die(mysql_error());
	$pid = mysql_insert_id();

	$newname = "$pid.png";
	move_uploaded_file($_FILES['fileField']['tmp_name'], "/var/www/html/images/$newname");

}



?>



<?php
//Grabs list
$product_list = "";
$sql = mysql_query("SELECT * FROM product");
$productCount = mysql_num_rows($sql);
if($productCount > 0){
	while($row=mysql_fetch_array($sql)){
		$idproduct = $row['idproduct'];
		$Name = $row['Name'];
		$Price = $row['Price'];
        $Available = $row['Available'];
		$product_list .= "$idproduct - $Available - $Name - $Price$ &nbsp; &nbsp; &nbsp; &bull; <a href='inventory_list.php?deleteid=$idproduct'>delete</a></br>";
	}

} else {
	$product_list = "You have no products listed in your store yet";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Inventory</title>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

	<div align="center" id="mainWrapper">	
	<?php include_once("../template_header.php");?>
	<div id="pageContent"><br/>
		<div align="left" style="margin-left: 24px;">
			<div align="right" style="margin-left: 32px">Add item</div>
			 <div align="left" style="margin-left: 24px;">
			<h2>Inventory List</h2>
			<?php echo $product_list; ?>
		</div>
		<h3> &darr; Add new inventory item form &darr;</h3>
		<form action="inventory_list.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
		<table width="90%" border="0" cellspacing="0" cellpadding="6">
			<tr>
				<td width="20%"> Product Name</td>
				<td width="80%"><label>
					<input name="Name" type="text" id="Name" size="64"/>
				</label></td>
			</tr>
			<tr>
				<td>Product Price</td>
				<td><label>
					$
					<input name="Price" type="number" id="Price" size="12"/>
				</label></td>
			</tr>
			<tr>
				<td>Amount</td>
				<td><label>
					<textarea name="details" id="details" cols="15" rows="1"></textarea>
                    </label>
                </td>
            </tr>
                    <tr>
				<td>Product Image</td>
				<td><label><input type="file" name="fileField"/>
				</label></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><label>
					<input type="submit" name="button" id="button" value="Add Item"/>
				</label></td>
			</tr>
		</table>
	</form>




	<?php include_once("../template_footer.php");?>
	</div>
</body>
</html>