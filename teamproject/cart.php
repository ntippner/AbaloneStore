<?php
session_start();

require 'db_connection.php';

//check for log in
if(!isset($_SESSION['record']))
{
	header("Location: login.php");
}
else
{
	$record = $_SESSION['record'];
	echo "You are logged in ".$record['FirstName']." ".$record['LastName'].".";
}

function getCartItems()
{
	global $dbConn,$record;
	switch (isset($_POST['CheckCart'])) {
		case '0':
			#check for record in cart_item table
			$sql = "SELECT CustomerUsername, CART_ITEM.ProductID, Quantity, QuantityInStock
					FROM CART_ITEM LEFT JOIN PRODUCT ON CART_ITEM.ProductID=PRODUCT.ProductID
					WHERE CustomerUsername=:CustomerUsername
					AND CART_ITEM.ProductID=:ProductID";
			
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername'],":ProductID"=>$_POST['ProductID']));
			$newRecord = $stmt -> fetch();
			
			#add record
			$runSQL = true;
			if (empty($newRecord))
			{
				$sql = "INSERT INTO CART_ITEM(CustomerUsername, ProductID, Quantity)
						VALUES(:CustomerUsername, :ProductID, 1)";
			}
			else if(!isset($_POST['NewQuantity']))
			{
				if($newRecord['Quantity']<$newRecord['QuantityInStock'])
				{
					#add 1 to quantity
					$sql = "UPDATE CART_ITEM
						SET Quantity=Quantity+1
						WHERE CustomerUsername=:CustomerUsername
						AND ProductID=:ProductID";
				}
				else
					$runSQL = false;
			}
			else {
				if($_POST['NewQuantity']<1)
				{
					#remove cart_item
					$sql = "DELETE FROM CART_ITEM
						WHERE CustomerUsername=:CustomerUsername
						AND ProductID=:ProductID";
				}
				else if($_POST['NewQuantity']<=$newRecord['QuantityInStock'])
				{
					#set new quantity for cart_item
					$sql = "UPDATE CART_ITEM
						SET Quantity=:Quantity
						WHERE CustomerUsername=:CustomerUsername
						AND ProductID=:ProductID";
					$stmt = $dbConn -> prepare($sql);
					$stmt -> execute(array(":Quantity"=>$_POST['NewQuantity'],":CustomerUsername"=>$record['CustomerUsername'],":ProductID"=>$_POST['ProductID']));
					$runSQL = false;
				}
				else
					$runSQL = false;
			}
			if($runSQL)
			{
				$stmt = $dbConn -> prepare($sql);
				$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername'],":ProductID"=>$_POST['ProductID']));
			}
		
		default:
			#get all cart records for customer
			$sql = "SELECT CART_ITEM.ProductID, CustomerUsername, Quantity, UnitPrice, ProductName, QuantityInStock
					FROM CART_ITEM LEFT JOIN PRODUCT ON PRODUCT.ProductID=CART_ITEM.ProductID
					WHERE CustomerUsername=:CustomerUsername";
			
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername']));
			$records = $stmt -> fetchAll();
			#create a table for the cart
			if(empty($records))
				echo'<h2>No items in the shopping cart.</h2>';
			else
				createCartTable($records);
			break;
	}
	
	
}
function createCartTable($records){
	global $dbConn;
	echo '<br><br>';
	echo '<table border="1"><tr>';
	echo '<th colspan="2"><h2>Shopping Cart</h2></th>';
	echo '<th>Price</th>';
	echo '<th>Quantity</th>';
	echo '<th> </th>';
	echo '</tr>';
	foreach ($records as $rec)
	{
		echo '<tr><td><img src="https://tylerprice.co/vandv/wp-content/uploads/sites/10/2017/02/coming-soon-300x300.jpg" alt="Coming Soon" style="width:50px;height:50px;"></td>';
		echo '<td>'.$rec['ProductName'].'</td>';
		echo '<td>$'.$rec['UnitPrice'].'</td>';
		echo '<td><form method="post""><input type = "hidden" name = "ProductID" value = "'.$rec['ProductID'].'" />';
		echo '<input type="number" name="NewQuantity" value= "'. min($rec['Quantity'],$rec['QuantityInStock']) .'" min="0" max="'. $rec['QuantityInStock'] .'"></td>';
		echo '<td><input type="submit" value="Update"></form></td></tr>';
	}
	$subTotal = getSubTotal($records);
	if($subTotal>0)
	{
		$subTotal=round($subTotal,2);
		$shipping = 10.99;
		$tax = round(($subTotal+$shipping)*(0.0725),2);
		$total=$subTotal+$tax+$shipping;
		
		echo'<tr><td colspan="3"></td>';
		echo'<td><h4>Subtotal:</h4></td>';
		echo'<td><h4>$'.$subTotal.'</h4></td></tr>';
		
		echo'<tr><td colspan="3"></td>';
		echo'<td><h4>Shipping & Handling:</h4></td>';
		echo'<td><h4>$'.$shipping.'</h4></td></tr>';
		
		echo'<tr><td colspan="3"></td>';
		echo'<td><h4>Tax:</h4></td>';
		echo'<td><h4>$'.$tax.'</h4></td></tr>';
		
		echo'<tr><td colspan="5"><h4> </h4></td></tr>';
		echo'<tr><td colspan="3"></td>';
		echo'<td><h4>Total:</h4></td>';
		echo'<td><h4>$'.$total.'</h4></td></tr>';
		
		echo'<form method="post" action="checkout.php">';
		$_SESSION['values']=array('Records'=>$records,'SubTotal'=>$subTotal,'ShippingCost'=>$shipping,'TaxAmount'=>$tax,'TotalAmount'=>$total);
	}
	echo '</table><input type="submit" value="Proceed to Checkout"></form>';
}
function getSubTotal($records)
{
	global $dbConn, $record;
	
	$sql = "SELECT SUM(UnitPrice*Quantity) AS SubTotal
			FROM `CART_ITEM` LEFT JOIN `PRODUCT` ON CART_ITEM.ProductID=PRODUCT.ProductID
			WHERE CustomerUsername=:CustomerUsername";
	
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername']));
	$newRecord = $stmt -> fetch();
	
	if (!empty($newRecord))
	{
		return($newRecord['SubTotal']);
	}
	return 0;
}
?>


<!DOCTYPE html>
<html>
<style>

body {
	background: #A8EEFF;
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
}

button, input[type=submit] {
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    background-color: #00CD97;
}

select {
	width: 20%;
}

input[type=submit]:hover, button:hover {
    opacity: 0.8;
}

select {
  -webkit-appearance: none;
}

.logo {
    height: auto; 
    width: auto; 
    max-width: 100px; 
    max-height: 100px;
}

table {
			width:100%;
		}
</style>
<head>
	<title>Store</title>
	<script>
		function confirmLogout(event) {
			var logout = confirm("Do you really want to log out?");
			if (!logout)
				return false;
			else
				return true;
		}
	</script>
</head>
<body>
	<form method="post" action="logout.php">
	<input type="submit" value="Logout" onclick="return confirmLogout()"/>
	</form>
	<div align="center" class="storelogo">
			<h1>Abalone Store</h1>
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
	<center>
	<?php
		getCartItems();
	?>
	<br>
	</center>
	<form method="post" action="index.php">
	<input type="submit" value="Home" />
	</form>
</body>
</html>