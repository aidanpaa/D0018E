<?php   
session_start();
if(!isset($_SESSION["customer"])){
  header("location: customer_login.php");
  exit();
}
$customerID = preg_replace('#[^0-9]#i','', $_SESSION["id_cust"]);
$customer = $_SESSION["customer"];
$password = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["password_cust"]);
$sortType = "Name";

include "../storescripts/connect_to_mysql.php";
$sql = mysql_query("SELECT * FROM customer WHERE idCustomer='$customerID' AND Email='$customer' AND Password='$password' LIMIT 1");


$existCount = mysql_num_rows($sql);
if($existCount == 0){
  echo "Your login session data is not on record in the database";
  exit();
}

/*
############ Check Out Button press ##########
*/
if(isset($_POST["check_out"])){  
	if(isset($_SESSION["shopping_cart"])){
		foreach($_SESSION["shopping_cart"] as $keys => $values){
					$total = $total + ($values["item_quantity"] * $values["item_price"]);
		}
		include "../storescripts/connect_to_mysql.php";
		$cart = mysql_query("SELECT idcart FROM cart WHERE customer_idcustomer = '$customerID' LIMIT 1");
		$row = mysql_fetch_array($cart);
		$cartID = $row["idcart"];
		
		mysql_query("INSERT INTO `order` (`TotalPrice`, `customerID`) VALUES ('$total', '$customerID')");
		mysql_query("DELETE FROM `productInCart` WHERE `cart_idcart`=$cartID");
		mysql_query("DELETE FROM `cart` WHERE `customer_idCustomer`=$customerID");
		header("location: order_complete.php");
		exit();

    } else {
		echo '<script>alert("SHOPPING CART NOT CREATED")</script>'; 
	}
}

/*
############ Add To Cart Button press ##########
*/
if(isset($_POST["add_to_cart"])){

		if(isset($_SESSION["shopping_cart"]))  
		{
			$cart = mysql_query("SELECT idcart FROM cart WHERE customer_idcustomer = '$customerID' LIMIT 1");
			$row = mysql_fetch_array($cart);
			$cartID = $row["idcart"];
			$quantity = $_POST["quantity"];
			$productID = $_GET["id"];
			$row2 = mysql_fetch_array(mysql_query("SELECT Available FROM product WHERE idproduct = '$productID' LIMIT 1"));
			$available = $row2['Available'];
			if($quantity <= $available){
				
				mysql_query("UPDATE product SET Available = Available-$quantity WHERE idproduct = $productID");
				mysql_query("INSERT INTO `productInCart` (`amount`, `product_idproduct`, `cart_idcart`) VALUES ('$quantity', '$productID', '$cartID')");
				$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");  
				if(!in_array($_GET["id"], $item_array_id))  
				{  
					$count = count($_SESSION["shopping_cart"]);  
					$item_array = array(  
                     'item_id'               =>     $_GET["id"],  
                     'item_name'               =>     $_POST["hidden_name"],  
                     'item_price'          =>     $_POST["hidden_price"],  
                     'item_quantity'          =>     $_POST["quantity"]  
					);  
					$_SESSION["shopping_cart"][$count] = $item_array;  
					foreach($_SESSION["shopping_cart"] as $keys => $values){
						$total = $total + ($values["item_quantity"] * $values["item_price"]);
					}
					mysql_query("UPDATE cart SET price = $total WHERE customer_idcustomer = $customerID");
				
				}

				else  
				{  
					mysql_query("UPDATE product SET Available = Available+$quantity WHERE idproduct = $productID");
					echo '<script>alert("Item Already Added")</script>';  
					echo '<script>window.location="index.php"</script>';  
				}  
			} else {
				echo '<script>alert("You cannot order more products than there are available!")</script>';  
                echo '<script>window.location="index.php"</script>'; 
			}
		} else {
	
			mysql_query("INSERT INTO `cart` (`price`, `customer_idCustomer`) VALUES ('$total', '$customerID')");
			$cart = mysql_query("SELECT idcart FROM cart WHERE customer_idcustomer = '$customerID' LIMIT 1");
			$row = mysql_fetch_array($cart);
			$cartID = $row["idcart"];
			$quantity = $_POST["quantity"];
			$productID = $_GET["id"];
			$row2 = mysql_fetch_array(mysql_query("SELECT Available FROM product WHERE idproduct = '$productID' LIMIT 1"));
			$available = $row2['Available'];
			if($quantity <= $available){
				mysql_query("UPDATE product SET Available = Available-$quantity WHERE idproduct = $productID");
				mysql_query("INSERT INTO `productInCart` (`amount`, `product_idproduct`, `cart_idcart`) VALUES ('$quantity', '$productID', '$cartID')");

				$item_array = array(  
				'item_id'               =>     $_GET["id"],  
				'item_name'               =>     $_POST["hidden_name"],  
				'item_price'          =>     $_POST["hidden_price"],  
				'item_quantity'          =>     $_POST["quantity"]  
				);  
				$_SESSION["shopping_cart"][0] = $item_array;  
		   
				foreach($_SESSION["shopping_cart"] as $keys => $values){
					$total = $total + ($values["item_quantity"] * $values["item_price"]);
				}
				mysql_query("UPDATE cart SET price = $total WHERE customer_idcustomer = $customerID");
		
			} else {
				echo '<script>alert("You cannot order more products than there are available!")</script>';  
                echo '<script>window.location="index.php"</script>';  
			}
       
		}  
 }  
 if(isset($_GET["action"]))  
 {  
	
	if($_GET["action"] == "sort"){
		$sortType = $_GET["type"];
	}
	if($_GET["action"] == "Logout"){
		session_unset();
		header("location: customer_login.php");
	}
	  if($_GET["action"] == "delete")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["item_id"] == $_GET["id"])  
                {  
					$productID = $_GET["id"];
					$amounttoremove = $_GET["amount"];
					mysql_query("UPDATE product SET Available = Available+$amounttoremove WHERE idproduct = $productID");

			
					$total = 0;
					foreach($_SESSION["shopping_cart"] as $keys => $values){
						$total = $total + ($values["item_quantity"] * $values["item_price"]);
					}
					mysql_query("UPDATE 'cart' SET TotalPrice = '$total' WHERE customer_idcustomer = $customerID");
                    unset($_SESSION["shopping_cart"][$keys]);  
					echo '<script>alert("Item Removed")</script>';  
                    echo '<script>window.location="index.php"</script>';  
                }  
           }  
      }  
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
 
           <br />  
           <div class="container" style="width:700px;">  
		   <?php include_once("../template_header.php");?>
				
                <h3 align="center">1-up Boiz Shopping Cart</h3><br />
                <div class="dropdown">
                <button class="dropbtn">Order by</button>
                <div class="dropdown-content">
				<a href="index.php?action=sort&type=Price">Price</a>
				<a href="index.php?action=sort&type=Rating">Rating</a>
				</div>
                </div>
                        
               <br>
               <br>
                <?php  
				if ($sortType == "Rating"){
					$result = mysql_query("SELECT * FROM product ORDER BY Rating/TotalRaters DESC");
				} else if ($sortType == "Price" ) {
					$result = mysql_query("SELECT * FROM product ORDER BY Price ASC");
				} else {
					$result = mysql_query("SELECT * FROM product ORDER BY Name ASC");

				}
				
                if(mysql_num_rows($result) > 0)  
                {  
                     while($row = mysql_fetch_array($result))  
                     {  
                ?>  
                <div class="col-md-4">  
                     <form method="post" action="index.php?action=add&id=<?php echo $row["idproduct"]; ?>">  
                          <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px;" align="center">  
                               <img src="../images/<?php echo $row["idproduct"]; ?>.png" class="img-responsive" /><br />  
                               <h4 class="text-info"><a href="http://server.beldrama.com//login/productspec.php?id=<?php echo $row["idproduct"]; ?>"> <?php echo $row["Name"]; ?></a></h4>  
                               <h4 class="text-danger">$ <?php echo $row["Price"]; ?></h4>
                            <h4 class="text-info">Rating: <?php echo $row["Rating"]/$row["TotalRaters"]; ?></h4>
                              <h4 class="text-info">In stock: <?php echo $row["Available"]; ?></h4>
                               <input type="text" name="quantity" class="form-control" value="1" />  
                               <input type="hidden" name="hidden_name" value="<?php echo $row["Name"]; ?>" />  
                               <input type="hidden" name="hidden_price" value="<?php echo $row["Price"]; ?>" />  
                               <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />

                          </div>  
                     </form>  
                </div>  
                <?php  
                     }  
                }  
                ?>  
                <div style="clear:both"></div>  
                <br />  
                <h3>Order Details</h3>  
                <div class="table-responsive">  
                     <table class="table table-bordered">  
                          <p><tr>  
                               <th width="40%">Item Name</th>  
                               <th width="10%">Quantity</th>  
                               <th width="20%">Price</th>  
                               <th width="15%">Total</th>  
                               <th width="5%">Action</th>  
                         </tr> </p> 
                          <?php   
                          if(!empty($_SESSION["shopping_cart"]))  
                          {  
                               $total = 0;  
                               foreach($_SESSION["shopping_cart"] as $keys => $values)  
                               {  
                          ?> 
                         <p>
                          <tr>  
                               <td><?php echo $values["item_name"]; ?></td>  
                               <td><?php echo $values["item_quantity"]; ?></td>  
                               <td>$ <?php echo $values["item_price"]; ?></td>  
                               <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>  
                               <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>&amount=<?php echo $values["item_quantity"]; ?>"><span class="text-danger">Remove</span></a></td>  
                          </tr></p>  
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
                          ?>
                     </table>
                     <form method="post" action="">

                        <input type="submit" name="check_out" style="margin-top:5px;" class="btn btn-success" value="Check out" />
                     </form>
                </div>
           </div>
           <br />
          <?php include_once("../template_footer.php");?>
      </body>
 </html>