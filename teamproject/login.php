<?php
	session_start();
	$incorrect=false;
	if(isset($_POST['CustomerUsername']))
	{
		require 'db_connection.php';
		
		$sql = "SELECT *
				FROM CUSTOMER
				WHERE CustomerUsername = :CustomerUsername
				AND encryptedPassword = :encryptedPassword";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":CustomerUsername"=>$_POST['CustomerUsername'],":encryptedPassword" => hash("sha1", $_POST['password'])));
		$record = $stmt->fetch();
		if(empty($record))
		{
			$incorrect = true;
		}
		else
		{
			$sql = "INSERT INTO LOG(CustomerUsername, event)
					VALUES(:CustomerUsername, 'Logged In')";
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute(array(":CustomerUsername"=>$record['CustomerUsername']));
			
			$_SESSION['record']=$record;
			header("Location: index.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<style>

body {
	background: #A8EEFF;
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
}

h1, h2 {
	font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
}

input[type=text], input[type=password] {
    width: 30%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

input[type=submit], button[type=register] {
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 30%;
}

input[type=submit] {
	background-color: #00CD97;
}

input[type=submit]:hover, button[type=register]:hover {
    opacity: 0.8;
}

.registerbtn {
    background-color: #00BFEC;
}

.imgcontainer {
    margin: 24px 0 12px 0;
}

.logo {
    height: auto; 
    width: auto; 
    max-width: 300px; 
    max-height: 300px;
}


.container {
    padding: 16px;
}


/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
	input[type=submit] {
		width: 100%;
	}
    .registerbtn {
       width: 100%;
    }
}
</style>
<center>
<head>
	<title>Login Page</title>
</head>
<body>
	<form method="post">
		<h1>Abalone Store</h1>
		<div class="storelogo">
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
		<h2>Log In</h2>
		<?php
			if($incorrect)
			{
				echo'<h4>Incorrect username or password.</h4>';
				echo"<input type='text' name='CustomerUsername' value='".$_POST['CustomerUsername']."' maxLength='20' required></input></br>
					<input type='password' name='password' placeholder='Password' maxLength='25' required></input></br>
					<input class='Button Submit' type='submit' value='submit'></br>";
			}
			else
			{
				echo"<input type='text' name='CustomerUsername' placeholder='Username' maxLength='20' required></input></br>
					<input type='password' name='password' placeholder='Password' maxLength='25' required></input></br>
					<input class='Button Submit' type='submit' value='Submit'></br>";
			}
		?>
	</form>
	<br>
	<form method="post" action="register.php"><button name='cart' type='register' class='registerbtn'>Register</button></form>
</body>
</center>
</html>