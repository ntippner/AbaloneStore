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
	function getVendors()
	{
		global $dbConn;
		$sql = "SELECT *
				FROM VENDOR";
		
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		$records = $stmt -> fetchAll();
		echo"Select Vendor: <select name='VendorID'>";
		foreach ($records as $record) {
			echo '<option value="'.$record['VendorID'].'">'.$record['CompanyName'].'</option>';
		}
		echo"<select>";
	}
	function getCategories()
	{
		global $dbConn;
		$num=2;
		$sql = "SELECT *
				FROM CATEGORY
				WHERE CategoryID>:num";
		
		$stmt = $dbConn -> prepare($sql);
		
		$stmt -> execute(array(":num"=>$num));
		$records = $stmt -> fetchAll();
		echo"Select Category :<select name='CategoryID'>";
		foreach ($records as $record) {
			echo '<option value="'.$record['CategoryID'].'">'.$record['CategoryName'].'</option>';
		}
		echo"<select>";
	}
   	function displayForm(){
   	
	echo "<form method='post'>";
	getVendors();
	echo"<br/>
			Product Name: <input required type='text' name='ProductName'><br/>
			Description: <br/>
			<textarea required name= 'Description' rows='15' col='60' placeholder= 'Enter Product Description'></textarea>
      		<br />
			Unit Price: <input required type='number' min='0.01' step='0.01' name='UnitPrice'><br/>
			Quantity In Stock: <input required type='number' min='0' name='QuanityInStock'><br/>";
	echo getCategories();
	echo "<br/><input type='submit' name='submitAddP' value = 'submit'></form><br>";
	}
	
	if(isset($_POST['submitAddP'])) {
		$table = "Product";
		global $dbConn;
		$vendorId = $_POST['VendorID']; 
		$productName = $_POST['ProductName'];
		$description = $_POST['Description'];
		$unitPrice = $_POST['UnitPrice'];
		$quantityInStock = $_POST['QuanityInStock'];
		$releaseDate = "CURDATE()";
		$categoryId = $_POST['CategoryID'];
		//$sql = "INSERT INTO PRODUCT (ProductID, VendorID, ProductName, Description, UnitPrice, QuantityInStock, ReleaseDate, CategoryID )
		//		VALEUS (NULL, :vendorId, :productName, :description, :unitPrice, :QuantityInStock, :releaseDate, :categoryId)";
		$stmt = $dbConn -> prepare("INSERT INTO PRODUCT
									VALUES (NULL, ". $vendorId. ",'" .$productName. "' , '".$description."', ".$unitPrice. ", ".$quantityInStock. ", ".$releaseDate.", ".$categoryId.")");
				
		echo "<h3>Your product has been added.</h3>";
		
		$stmt -> execute();
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Add Product</title>
  <meta name="Add Product" content="">
  <meta name="Sean Figel" content="S-Alien">

  <meta name="viewport" content="width=device-width; initial-scale=1.0">

  <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
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
		
		.logo {
		    height: auto; 
		    width: auto; 
		    max-width: 100px; 
		    max-height: 100px;
		}
		h3 {
			text-align: center;
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
			<h3>Add Product</h3>
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
  <div>
<form method="post" action="index.php"><button name='home' type='home'>Home</button></form>
<?php
if(!isset($_POST['submitAddP']))
	displayForm();
?>
<form action ="manageProduct.php">
    <input type='submit' name='Back' value = 'Go Back'>
</form>
  </div>
</body>
</html>