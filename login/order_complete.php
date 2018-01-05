<?php   
session_start();
if(!isset($_SESSION["customer"])){
  header("location: customer_login.php");
  exit();
}

$customerID = preg_replace('#[^0-9]#i','', $_SESSION["id_cust"]);
$customer = $_SESSION["customer"];
$password = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["password_cust"]);

require_once "../storescripts/connect_to_mysql.php";

if(!isset($_SESSION["shopping_cart"])){
	echo '<script>alert("SHOPPING CART NOT CREATED")</script>'; 
}
 
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>1-up Boiz Store</title>  
			<link rel="stylesheet" href="../styles/style.css" type="text/css">
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>
          <?php include_once("../template_header.php");?>
           <h3>Order Details</h3>  
                <div class="table-responsive">  
                     <table class="table table-bordered">  
                          <tr>  
                               <th width="40%">Item Name</th>  
                               <th width="10%">Quantity</th>  
                               <th width="20%">Price</th>  
                               <th width="15%">Total</th>   
                          </tr>  
                          <?php   
                          if(!empty($_SESSION["shopping_cart"]))  
                          {  
                               $total = 0;  
                               foreach($_SESSION["shopping_cart"] as $keys => $values)  
                               {  
                          ?>  
                          <tr>  
                               <td><?php echo $values["item_name"]; ?></td>  
                               <td><?php echo $values["item_quantity"]; ?></td>  
                               <td>$ <?php echo $values["item_price"]; ?></td>  
                               <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>  
                          </tr>  
                          <?php  
                                    $total = $total + ($values["item_quantity"] * $values["item_price"]);  
                               }  
                          ?>  
                          <tr>  
                               <td colspan="3" align="right">Total</td>  
                               <td align="right">$ <?php echo number_format($total, 2); ?></td>  
                               <td></td>  

                          </tr>  
                          <?php  
                          }
						  unset($_SESSION["shopping_cart"]);
                          ?>  
                     </table>
                </div>  
      </body>  
 </html>