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
		button, input[type=reset] {
		    color: white;
		    padding: 14px 20px;
		    margin: 8px 0;
		    border: none;
		    cursor: pointer;
		    background-color: red;
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
			<h3>Edit Product</h3>
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
			<?php
			global $dbConn;
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
					$sql = "select p.ProductID, p.ProductName, p.Description, p.UnitPrice, p.QuantityInStock, p.ReleaseDate, c.CategoryName, v.CompanyName
					from PRODUCT p, VENDOR v, CATEGORY c
					where p.vendorid = v.vendorid
					and p.categoryid = c.categoryid and p.ProductID = ".$_GET['ProductID'];
					
					$stmt = $dbConn -> prepare($sql);
					$stmt -> execute();
					$records = $stmt -> fetchAll();
	
					if (!empty($records))
					{
						foreach($records as $record)
						{
							echo "<form method='post'>"; 
							echo "Product Name: <input required type='text' name='ProductName' value='".$record['ProductName']."'><br/>";
							echo "Description: <br/>";
							echo "<textarea required name= 'Description' rows='15' col='60'>".$record['Description']."</textarea>";
	      					echo "<br />";
							echo "Unit Price: <input required type='number' step='0.01' name='UnitPrice' value='".$record['UnitPrice']."'><br/>";
							echo "Quantity In Stock: <input required type='number' name='QuantityInStock' value='".$record['QuantityInStock']."'><br/>";
							//"Release Date: <input required type='text' name='ReleaseDate'><br/>"
							echo "<input type=\"hidden\" name=\"ProductID\" value=\"".$_GET['ProductID']."\">";
							echo "<input type='submit' name='submitEdtP' value = 'submit'><input type='reset' name='resetButton' value = 'reset'>";
							echo "</form>";
						}
					} else {
						echo "no such product found";
					}
			}
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$sql = "update PRODUCT set ProductName = :ProductName, Description = :Description, UnitPrice = :UnitPrice, QuantityInStock=:QuantityInStock
						where ProductID = :ProductID";
				$stmt = $dbConn->prepare($sql);                                  
				$stmt->bindParam(':ProductName', $_POST['ProductName'], PDO::PARAM_STR);
				$stmt->bindParam(':Description', $_POST['Description'], PDO::PARAM_STR);
				$stmt->bindParam(':UnitPrice', $_POST['UnitPrice'], PDO::PARAM_STR);
				$stmt->bindParam(':QuantityInStock', $_POST['QuantityInStock'], PDO::PARAM_INT);
				$stmt->bindParam(':ProductID', $_POST['ProductID'], PDO::PARAM_INT);
				try {
					$stmt->execute(); 
				} catch(Exception $e) {
					echo "failed to update..";
				}
				echo "<br>Values saved..go back to <a href='manageProduct.php'>manage products</a>";
			}
					
			?>


	</body>
</html>