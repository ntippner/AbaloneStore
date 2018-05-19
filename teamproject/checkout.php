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
	$values = $_SESSION['values'];
}
function getPaymentRecord($CustomerUsername)
{
	global $dbConn;
	$sql = "SELECT * 
			FROM PAYMENT LEFT JOIN ADDRESS ON PAYMENT.AddressID=ADDRESS.AddressID
			WHERE PAYMENT.CustomerUsername=:CustomerUsername";
	
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":CustomerUsername"=>$CustomerUsername));
	$newRecord = $stmt -> fetch();
	return $newRecord;
}
function insertAddress($CustomerUsername,$AddressLine1,$AddressLine2,$City,$State,$Country,$ZIP)
{
	global $dbConn;
	$sql = "INSERT INTO ADDRESS(CustomerUsername, AddressLine1, AddressLine2, City, State, Country, ZIP)
			Values(:CustomerUsername, :AddressLine1, :AddressLine2, :City, :State, :Country, :ZIP)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':CustomerUsername'=>$CustomerUsername,':AddressLine1'=>$AddressLine1,':AddressLine2'=>$AddressLine2,
		':City'=>$City, ':State'=>$State, ':Country'=>$Country, ':ZIP'=>$ZIP));
	return($dbConn -> lastInsertId());
}
function insertPayment($CustomerUsername,$FirstName,$LastName,$AddressID,$CardCompany,$CardNumber,$ExpirationMonth,$ExpirationYear)
{
	global $dbConn;
	$sql = "INSERT INTO PAYMENT(CustomerUsername, FirstName, LastName, AddressID, CardCompany, CardNumber, ExpirationMonth, ExpirationYear)
			VALUES(:CustomerUsername, :FirstName, :LastName, :AddressID, :CardCompany, :CardNumber, :ExpirationMonth, :ExpirationYear)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':CustomerUsername'=>$CustomerUsername,':FirstName'=>$FirstName,':LastName'=>$LastName,':AddressID'=>$AddressID,
		':CardCompany'=>$CardCompany,':CardNumber'=>$CardNumber,':ExpirationMonth'=>$ExpirationMonth,':ExpirationYear'=>$ExpirationYear));
	return($dbConn -> lastInsertId());
}
function insertInvoice($SubTotal,$TaxAmount,$TotalAmount,$CustomerUsername,$AddressID,$PaymentID,$ShippingCost)
{
	global $dbConn;
	$sql = "INSERT INTO INVOICE (SubTotal, TaxAmount, TotalAmount, CustomerUsername, AddressID, PaymentID, ShippingCost)
			VALUES(:SubTotal, :TaxAmount, :TotalAmount, :CustomerUsername, :AddressID, :PaymentID, :ShippingCost)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':SubTotal'=>$SubTotal,':TaxAmount'=>$TaxAmount,':TotalAmount'=>$TotalAmount,':CustomerUsername'=>$CustomerUsername,
		':AddressID'=>$AddressID,':PaymentID'=>$PaymentID,':ShippingCost'=>$ShippingCost));
	return($dbConn -> lastInsertId());
}
function getCartList($CustomerUsername)
{
	global $dbConn;
	$sql = "SELECT Quantity, CART_ITEM.ProductID, UnitPrice
			FROM CART_ITEM LEFT JOIN PRODUCT ON CART_ITEM.ProductID=PRODUCT.ProductID
			WHERE CART_ITEM.CustomerUsername=:CustomerUsername";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":CustomerUsername"=>$CustomerUsername));
	return $stmt -> fetchAll();
}
function processOrder($cartList,$InvoiceID)
{
	global $record;
	$LineNumber=0;
	foreach ($cartList as $cartItem) {
		$LineNumber++;
		$TotalPrice=round($cartItem['UnitPrice']*$cartItem['Quantity'],2);
		decreaseInventory($cartItem['Quantity'],$cartItem['ProductID']);
		insertLineItem($InvoiceID,$LineNumber,$cartItem['ProductID'],$cartItem['Quantity'],$TotalPrice);
		deleteCartItem($record["CustomerUsername"], $cartItem['ProductID']);
	}
}
function validInventory($Quantity,$ProductID)
{
	global $dbConn;
	$sql = "SELECT *
			FROM PRODUCT
			WHERE ProductID=:ProductID AND QuantityInStock>=:Quantity";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":ProductID"=>$ProductID,':Quantity'=>$Quantity));
	$newRecord = $stmt -> fetch();
	if(empty($newRecord))
		return false;
	else
		return true;
}
function decreaseInventory($Quantity,$ProductID)
{
	global $dbConn;
	$sql = "UPDATE PRODUCT
			SET QuantityInStock=QuantityInStock-:Quantity
			WHERE ProductID=:ProductID";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':Quantity'=>$Quantity,':ProductID'=>$ProductID));
}
function insertLineItem($InvoiceID,$LineNumber,$ProductID,$Quantity,$TotalPrice)
{
	global $dbConn;
	$sql = "INSERT INTO LINE_ITEM (InvoiceID,LineNumber,ProductID,Quantity,TotalPrice)
			VALUES(:InvoiceID,:LineNumber,:ProductID,:Quantity,:TotalPrice)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':InvoiceID'=>$InvoiceID,':LineNumber'=>$LineNumber,':ProductID'=>$ProductID,':Quantity'=>$Quantity,':TotalPrice'=>$TotalPrice));
	return($dbConn -> lastInsertId());
}
function deleteCartItem($CustomerUsername,$ProductID)
{
	global $dbConn;
	$sql = "DELETE FROM CART_ITEM
		WHERE CustomerUsername=:CustomerUsername
		AND ProductID=:ProductID";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(':CustomerUsername'=>$CustomerUsername,':ProductID'=>$ProductID));
}
function createForm()
{
	echo "<form method='post'>
		<table border='1'>
		<tr><td align='right'><p class='required'>First Name: </td><td><input class='mediumInput' type='text' name='FirstName' placeholder='First Name' maxLength='25' required></td></tr>
		<tr><td align='right'><p class='required'>Last Name: </td><td><input class='mediumInput' type='text' name='LastName' placeholder='Last Name' maxLength='25' required></td></tr>
		<tr><td align='right'><p class='required'>Address1: </td><td><input class='longInput' type='text' name='AddressLine1' placeholder='Address1' maxLength='45' required></td></tr>
		<tr><td align='right'>Address1: </td><td><input class='longInput' type='text' name='AddressLine2' placeholder='Address2' maxLength='45'></td></tr>
		<tr><td align='right'><p class='required'>City: </td><td><input class='mediumInput' type='text' name='City' placeholder='City' maxLength='45' required></td></tr>
		<tr><td align='right'><p class='required'>State: </td><td><input class='tinyInput' type='text' name='State' placeholder='State' maxLength='2' style='text-transform:uppercase' required></td></tr>
		<tr><td align='right'><p class='required'>ZIP: </td><td><input class='smallInput' type='text' name='ZIP' placeholder='ZIP' maxLength='5' required></td></tr>
		<tr><td align='right'><p class='required'>Payment Method: </td><td><select name='CardCompany' required>
			<option value='VISA'>VISA</option>
			<option value='MC'>MasterCard</option>
			<option value='AmEx'>American Express</option>
		</select></td></tr>
		<tr><td align='right'><p class='required'>Card Number: </td><td><input class='longInput' type='text' name='CardNumber' placeholder='CardNumber' maxLength='19' required></td>
		</tr>
		<tr><td align='right'><p class='required'>Expiration: </td><td><input type='number' name='ExpirationMonth' placeholder='MM' min='1' max='12' required>
		<input type='number' name='ExpirationYear' placeholder='YYYY'  min='2017' max='3000' required></td></tr>
		<tr><td></td><td><input type='submit' value='Add Payment Method'></td></tr>
		</table>
	</form>";
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

.required::before {
	content: "* ";
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
  		<br>
	<center>
	<?php
		if(isset($_POST["FirstName"]))
		{
			$FirstName=$_POST["FirstName"];
			$LastName=$_POST["LastName"];
			$AddressLine1=$_POST["AddressLine1"];
			$AddressLine2=$_POST["AddressLine2"];
			$City=$_POST["City"];
			$State=$_POST["State"];
			$Country="USA";
			$ZIP=$_POST["ZIP"];
			$CardCompany=$_POST["CardCompany"];
			$CardNumber=$_POST["CardNumber"];
			$ExpirationMonth=$_POST["ExpirationMonth"];
			$ExpirationYear=$_POST["ExpirationYear"];
			$AddressID = insertAddress($record["CustomerUsername"], $AddressLine1, $AddressLine2, $City, $State, $Country, $ZIP);
			$PaymentID = insertPayment($record["CustomerUsername"], $FirstName, $LastName, $AddressID, $CardCompany, $CardNumber, $ExpirationMonth, $ExpirationYear);
		}
		$info = getPaymentRecord($record["CustomerUsername"]);
		if(!empty($info))
		{
			if (!isset($_POST['placeOrder']))
			{
				echo"<form method='post'><button name='placeOrder' type='submit'>Click to place order</button></form>";
			}
			else
			{
				$cartList=getCartList($record["CustomerUsername"]);
				$valid=true;
				foreach ($cartList as $cartItem)
				{
					if(!validInventory($cartItem['Quantity'],$cartItem['ProductID']))
						return false;
				}
				if($valid)
				{
					$InvoiceID=insertInvoice($values["SubTotal"], $values["TaxAmount"], $values["TotalAmount"], $info["CustomerUsername"], $info["AddressID"], $info["PaymentID"], $values["ShippingCost"]);
					processOrder($cartList,$InvoiceID);
					echo"<h3>Thank you for your order, ".$record["FirstName"].".</h3>";
				}
				else {
					echo"Did not process the order, ".$record["FirstName"].".";
				}
			}
			
		}
		else
			createForm();
	?>
	<br>
	</center>
	<form method="post" action="index.php">
	<input type="submit" value="Home">
	</form>
	
</body>
</html>