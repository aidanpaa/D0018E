<?php
session_start();
if(isset($_SESSION["customer"])){
	header("location: index.php");
	exit();
}

if(isset($_POST["email"]) && isset($_POST["password"])){
	$customer = $_POST["email"];
	$password = preg_replace('#[^A-Za-z0-9]#i','', $_POST["password"]);

	include "../storescripts/connect_to_mysql.php";
	$sql = mysql_query("SELECT idCustomer FROM customer WHERE Email='$customer' AND Password='$password' LIMIT 1");

	$existCount = mysql_num_rows($sql);

	if($existCount == 1){
		$row = mysql_fetch_array($sql);
		$id = $row["idCustomer"];
		$_SESSION["id_cust"] = $id;
		$_SESSION["customer"] = $customer;
		$_SESSION["password_cust"] = $password;
		header("location: index.php");
		exit();
	}else{
		echo 'That information is incorrect, try again <a href="index.php">Click here</a>';
		exit();
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
			<h2>Please log in to purchase from the store</h2>
			<form id="form1" name="form1" method="post" action="customer_login.php">Email:<br/>
				<input name="email" type="text" id="email" size="40"/><br/><br/>
				Password:<br/>
				<input name="password" type="password" id="password" size="40"/>
				<br/>
				<br/>
				<br/>
					<input type="submit" name="button" id="button" value="Log In"/>
				<br/>
				<br/>
				Don't have an account yet? Register <a href="http://83.226.17.35/login/customer_register.php">HERE</a>
				
			</form>
			<p>&nbsp;</p>
		</div>
		<br/>
		<br/>
	</div>
	<?php include_once("../template_footer.php");?>
	
	</div>
	

</body>
</html>