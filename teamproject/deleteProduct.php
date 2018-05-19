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
	$message = "<h3>Are you sure that you would like to delete the product?</h3>";
	if(isset($_POST['Delete'])) {
					global $dbConn;	
				$sql = "DELETE FROM PRODUCT
					WHERE ProductID =:ProductId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute( array(":ProductId"=> $_GET['ProductID']));
		$message = "<h3>Product has been deleted.</h3>";
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

  <title>new_file</title>
  <meta name="Delete Product" content="">
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
			<h3>Delete Product</h3>
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>		
  <div align="center">
<?php echo $message ?>

<?php
$buttonName="Back";
$buttonValue="Go Back";
if(!isset($_POST['Delete']))
{
	echo"<form method='post'>	
		<input type='submit' name='Delete' value = 'Delete'>
		</form>";
	$buttonName="DoNotDelete";
	$buttonValue="Do Not Delete";
}
?>
<form action ="manageProduct.php">
    <input type='submit' name='<?echo $buttonName;?>' value = '<?echo $buttonValue;?>'>
    </form>
  </div>
</body>
</html>