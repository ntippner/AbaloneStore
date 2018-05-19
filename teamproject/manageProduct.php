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

function isAdmin()
{
	global $dbConn,$record;
	$sql = "SELECT *
			FROM ADMIN
			WHERE CustomerUsername=:CustomerUsername
			AND Active>0;";
			
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":CustomerUsername"=>$record["CustomerUsername"]));
	$rec = $stmt -> fetch();
	
	if(empty($rec))
		return false;
	return true;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title>Manage Product</title>
		<meta name="description" content="Manage Product">
		<meta name="author" content="Debajyoti Banerjee">

		<meta name="viewport" content="width=device-width; initial-scale=1.0">
		<link rel="shortcut icon" href="http://hosting.otterlabs.org/favicon.ico">
		<link rel="apple-touch-icon" href="http://hosting.otterlabs.org/apple-touch-icon.png">
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
		
		th {
			background-color:#00CD97;
			color:white;
		}
		.addLink{
			color: red;
		}
		.addLink:visited{
			color: #f2f2f2;
		}
		.addLink:hover{
			color: white;
		}
		.logo {
		    height: auto; 
		    width: auto; 
		    max-width: 100px; 
		    max-height: 100px;
		}
		</style>
		<?
			if(!isAdmin())
			{
				echo '<script type="text/javascript">window.location = "index.php";</script>';
			}
		?>
	</head>
	<body>
		<div align="center" class="storelogo">
			<h1>Abalone Store</h1>
			<h3>Manage Products</h3>
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
  		<form method="post" action="index.php"><button name='home' type='home'>Home</button></form>
		<table border="1">
			<tr>
				<th>Category</th>
				<th>Product Name</th>
				<th>Description</th>
				<th>Price</th>
				<th>Quantity In Stock</th>
				<th>Release Date</th>
				<th>Vendor</th>
				<th><h4></b><a class='addLink' href='addProduct.php'>Add Product</a></h4></th>
			</tr>
			<?php
			global $dbConn;
			$sql = "select p.ProductID, p.ProductName, p.Description, p.UnitPrice, p.QuantityInStock, p.ReleaseDate, c.CategoryName, v.CompanyName
					from PRODUCT p, VENDOR v, CATEGORY c
					where p.VendorID = v.VendorID
					and p.CategoryID = c.CategoryID";
					
					$stmt = $dbConn -> prepare($sql);
					$stmt -> execute();
					$records = $stmt -> fetchAll();
	
					if (!empty($records))
					{
						foreach($records as $record)
						{
							echo"<tr>";
							echo"<td>". $record['CategoryName']."</td>";
							echo"<td>". $record['ProductName']."</td>";
							echo"<td>". $record['Description']."</td>";
							echo"<td>". $record['UnitPrice']."</td>";
							echo"<td>". $record['QuantityInStock']."</td>";
							echo"<td>". $record['ReleaseDate']."</td>";
							echo"<td>". $record['CompanyName']."</td>";
							echo"<td><a href='editProduct.php?ProductID=".$record['ProductID']."'>Edit</a>/<a href='deleteProduct.php?ProductID=".$record['ProductID']."'>Delete</a></td>";
							echo"</tr>";
						}
					}
			?>
		</table>

	</body>
</html>