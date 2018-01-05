<?php
session_start();
if(isset($_SESSION["manager"])){
	header("location: index.php");
	exit();
}




if(isset($_POST["username"]) && isset($_POST["password"])){
	$manager = preg_replace('#[^A-Za-z0-9]#i','', $_POST["username"]);
	$password = preg_replace('#[^A-Za-z0-9]#i','', $_POST["password"]);

	include "../storescripts/connect_to_mysql.php";
	$sql = mysql_query("SELECT id FROM admin WHERE username='$manager' AND password='$password' LIMIT 1");

	$existCount = mysql_num_rows($sql);

	if($existCount == 1){
		$row = mysql_fetch_array($sql);
		$id = $row["id"];
		$_SESSION["id"] = $id;
		$_SESSION["manager"] = $manager;
		$_SESSION["password"] = $password;
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
	<title>Admin</title>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
</head>
<body>

	<div align="center" id="mainWrapper">	
	<?php include_once("../template_header.php");?>
	<div id="pageContent"><br/>
		<div align="left" style="margin-left: 24px;">
			<h2>Please log in to manage the store</h2>
			<form id="form1" name="form1" method="post" action="admin_login.php">User Name:<br/>
				<input name="username" type="text" id="username" size="40"/><br/><br/>
				Password:<br/>
				<input name="password" type="password" id="password" size="40"/>
				<br/>
				<br/>
				<br/>
				
					<input type="submit" name="button" id="button" value="Log In"/>
				
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