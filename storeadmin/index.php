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
if(isset($_GET["action"]))  
{  
	if($_GET["action"] == "Logout"){
		session_unset();
		header("location: index.php");
	}
}
//Be sure to check that this manager SESSION value is in fact in the database
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

	<div align="center" id="mainWrapper">	
	<?php include_once("../template_header.php");?>
	<div id="pageContent"><br/>
		<div align="left" style="margin-left: 24px;">
			<h2>Login Successful! What would you like to do?</h2>
			<p><a href="inventory_list.php">Manange Inventory</a><br/>
            <p><a href="completedorders.php">View orders</a><br/>
                
	<?php include_once("../template_footer.php");?>
	</div>
</body>
</html>