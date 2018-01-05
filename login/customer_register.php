<?php
session_start();
if(isset($_SESSION["customer"])){
	header("location: customer_login.php");
	exit();
}

if(isset($_POST["email"]) && isset($_POST["password"])){
	$firstName = $_POST["FirstName"];
	$lastName = $_POST["LastName"];
	$address = $_POST["Address"];
	$city = $_POST["City"];
	$pnr = $_POST["Pnr"];
	$zip = $_POST["Zip"];
	$phone = $_POST["Phone"];
	$customer = $_POST["email"];
	$password = preg_replace('#[^A-Za-z0-9]#i','', $_POST["password"]);
	include "../storescripts/connect_to_mysql.php";
	mysql_query ("INSERT INTO `customer` (`FirstName`, `LastName`, `Address`, `City`, `Pnr`, `Zip`, `Phone`, `Email`, `Password`) VALUES ('$firstName', '$lastName', '$address', '$city', '$pnr', '$zip', '$phone', '$customer', '$password')");
	$sql = mysql_query("SELECT idCustomer FROM customer WHERE Email='$customer' AND Password='$password' LIMIT 1");
	$existCount = mysql_num_rows($sql);

	if($existCount == 1){
		$row = mysql_fetch_array($sql);
		$id = $row["idCustomer"];
		$_SESSION["id_cust"] = $id;
		$_SESSION["customer"] = $customer;
		$_SESSION["password_cust"] = $password;
		header("location: customer_login.php");
		exit();
	}else{
		echo 'You forgot to write the required fields, try again <a href="customer_register.php">Click here</a>';
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
			<h2>Register an account!</h2>
			<form id="form1" name="form1" method="post" action="customer_register.php">First Name:<br/>
				<input name="FirstName" type="text" id="FirstName" size="40"/><br/><br/>
				Last Name:<br/>
				<input name="LastName" type="text" id="LastName" size="40"/><br/><br/>
				Address:<br/>
				<input name="Address" type="text" id="Address" size="40"/><br/><br/>
				City:<br/>
				<input name="City" type="text" id="City" size="40"/><br/><br/>
				Personal Code Number*:<br/>
				<input name="Pnr" type="text" id="Pnr" size="40"/><br/><br/>
				Zip-Code:<br/>
				<input name="Zip" type="text" id="Zip" size="40"/><br/><br/>
				Phone Number:<br/>
				<input name="Phone" type="text" id="Phone" size="40"/><br/><br/>
				E-Mail*:<br/>
				<input name="email" type="text" id="email" size="40"/><br/><br/>
				Password*:<br/>
				<input name="password" type="password" id="password" size="40"/>
				<br/>
				<br/>
				<br/>
					<input type="submit" name="button" id="button" value="Submit"/>
				<br/>
				<br/>
				Already have an account? Log in <a href="http://83.226.17.35/login">HERE</a>
				
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