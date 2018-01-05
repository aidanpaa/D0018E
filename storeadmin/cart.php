<?php
session_start();
if(!isset($_SESSION["customer"])){
	header("location: customer_login.php");
	exit();
}

if(isset($_POST["myForm"])){
	$product = $_POST["myForm"];
	
}



$customerID = preg_replace('#[^0-9]#i','', $_SESSION["id"]);
$customer = $_SESSION["customer"];
$password = preg_replace('#[^A-Za-z0-9]#i','', $_SESSION["password"]);

require_once "../storescripts/connect_to_mysql.php";
$sql = mysql_query("SELECT * FROM customer WHERE idCustomer='$customerID' AND Email='$customer' AND Password='$password' LIMIT 1");


$existCount = mysql_num_rows($sql);
if($existCount == 0){
	echo "Your login session data is not on record in the database";
	exit();
}

//Be sure to check that this manager SESSION value is in fact in the database
?>

<?php
/*
if(isset($_POST['Amount'])){
	$Amount = mysql_real_escape_string($_POST['Amount']);
	$_SESSION["Cart"] = mysql_real_escape_string($_POST['Amount']);
	
}
*/

if(isset($_GET['idGet'])){
	$id = $_GET['idGet'];
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
			<?php
			$query = "SELECT idproduct, Name, Available, Price FROM product";
			$result = mysql_query($query);
			
			
			while ($row = mysql_fetch_array($result)){
				echo ("<tr><th>Image</th><th>Name</th><th>Available</th><th>Price</th><th>Add to basket</th></tr><tr><td align=center>");

				echo ('<img src=../images/'. $row['idproduct'].'.png width=50% height=50%/>');
				echo ("</td><td>");
				echo ($row['Name']);
				echo ("</td><td>");
				echo ($row['Available']);
				echo ("</td><td>");
				echo ($row['Price']);
				echo ("</td><td>");
				echo ("<a href='index.php?idGet=". $row['idproduct']."'>addtobasket</a>");
				//echo ("<input name='Amount' type='number' id='".$row['idproduct']."' size='1'/>");
				//echo ("<input type='submit' name='button".$row['idproduct']."' id='button".$row['idproduct']."' value='Add To Cart'/>");
				echo ("</td></tr>\n");
			}
			
			
			?>
			</form>
			</table>
			
			<script type="text/javascript">
			
				function sub(x) {

					if (value > 0){
						value = value - 1;
						Cookies.set(x, value);
					}
					document.getElementById(x).innerHTML = "Current items = " + value;

				}
				function add(x) {
					value = value + 1;
					Cookies.set(x, value);
					document.getElementById(x).innerHTML = "Current items = " + value;
				}
			
			
			/*
			
				var cart =[];
				var Item = function(name, price, count) {
					this.name = name;
					this.price = price;
					this.count = count;
				};
				
				function addItemToCart(name, price, count){
					for (var i in cart){
						if (cart[i].name === name){
							cart[i].count += count;
							return;
						}
					}
					var item = new Item(name, price, count);
					cart.push(item);
					saveCart();
				}
				

				
				
				function removeItemFromCart(name){ //Removes one item
					for (var i in cart){
						if(cart[i].name === name){
							cart[i].count --;
							if(cart[i].count === 0){
								cart.splice(i, 1);
							}
							break;
						}
					}
					saveCart();
				} 
				

				
				function removeItemFromCartAll(name){ //Removes all item name
					for (var i in cart){
						if (cart[i].name === name){
							cart.splice(i, 1);
							break;
						}
					}
					saveCart();
				}
				
				addItemToCart("Mario", 125, 128);
				addItemToCart("Mario", 125, 128);
				
				
				function clearCart(){	//Removes all items
					cart = [];
					saveCart();
				}
				
				function countCart(){	// -> return total count
					var totalCount = 0;
					for (var i in cart){
						 totalCount += cart[i].count;
						
					}
					
					return totalCount;
				}  
				
				console.log(countCart());
				
				function totalCart(){	//-> return total cost
					var totalCost = 0;
					for (var i in cart){
						totalCost += cart[i].price;
					}
					return totalCost;
				} 
				
				console.log(totalCart());

				
				//listCart() -> array of items
				
				function saveCart(){ 	//-> save to cookie
					localStorage.setItem("shoppingCart", JSON.stringify(cart));
				}
								
				
				
				//loadCart() -> load from cookie
			*/	
			
			
			
			</script>
			
	<?php include_once("../template_footer.php");?>
	</div>
</body>
</html>