<?php   
include('function.php'); 
session_start();
if(!isset($_SESSION["customer"])){
  header("location: customer_login.php");
  exit();
}

$customerID = preg_replace('#[^0-9]#i','', $_SESSION["id_cust"]);
$customer = $_SESSION["customer"];
$password = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["password_cust"]);

require_once"../storescripts/connect_to_mysql.php";
$sql = mysql_query("SELECT * FROM customer WHERE idCustomer='$customerID' AND Email='$customer' AND Password='$password' LIMIT 1");


$existCount = mysql_num_rows($sql);
if($existCount == 0){
  echo "Your login session data is not on record in the database";
  exit();
}

if(isset($_POST["comment"])){  
    $submit_id = $_GET['id'];
	$Comment = mysql_real_escape_string($_POST['txt']);
    include "../storescripts/connect_to_mysql.php";
    mysql_query("INSERT INTO `comments` (`comment`, `product_idproduct`) VALUES ('$Comment', '$submit_id')");
	$rating = $_POST['rate'];
	mysql_query("UPDATE product SET rating = rating+$rating WHERE idproduct = $submit_id");
	mysql_query("UPDATE product SET TotalRaters = TotalRaters+1 WHERE idproduct = $submit_id");


	
}







 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>1-up Boiz Store</title>  
			<link rel="stylesheet" href="../styles/style.css" type="text/css">

    </head>
    <body>
        <?php include_once("../template_header.php");?>
        
        <?php productSpec(); ?>

      </body>  
 </html>