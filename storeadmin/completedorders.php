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
<!DOCTYPE html>
<html>
	<head>
		<title>Admin</title>
		<link rel="stylesheet" href="../styles/style.css" type="text/css">
		</head>
		<body>
			<div align="center" id="mainWrapper">
				<?php include_once("../template_header.php");?>
				<?php
                            
							$order = mysql_query("SELECT * FROM `order`");
                            $mail = mysql_query("SELECT * FROM `customer`");
                            if(mysql_num_rows($order) > 0){
                                while($row = mysql_fetch_array($order)){
                                    
                                    echo $row["idorder"];
									$order_customer_id = $row["customerID"];
									echo "  -  ";
									$mail = mysql_query("SELECT Email FROM customer WHERE idCustomer=$order_customer_id LIMIT 1");
									$row2 = mysql_fetch_array($mail);
									echo $row2["Email"];
									echo "  -  ";
									echo $row["TotalPrice"];
									echo "</br>";
                                              			   
				           
                                    
                                }
                            }
							
                            if(mysql_num_rows($order) > 0){  
								while($row = mysql_fetch_array($order)){
                                    ?>
				<table style="width:10%" align="center">
					<tr>
						<?php echo $row["idorder"], $row["FirstName"]; ?>
					</tr>
				</table>
				<?php
                                
								}
							} 

							?>
				
			</div>
            <?php include_once("../template_footer.php");?>
		</body>
	</html>