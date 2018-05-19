<?php
	session_start();
	$passwordMatch=false;
	$retry=false;
	$taken=false;
	if(isset($_POST['password']))
	{
		$retry=true;
		if($_POST['password']==$_POST['confirmPassword'])
			$passwordMatch=true;
		if(is_numeric($_POST['Phone1'])&&is_numeric($_POST['Phone2'])&&is_numeric($_POST['Phone3']))
			$phone = $_POST['Phone1']."-".$_POST['Phone2']."-".$_POST['Phone3'];
	}
	
	if($passwordMatch)
	{
		require 'db_connection.php';
		
		//check username and email
		$sql = "SELECT *
		FROM CUSTOMER
		WHERE CustomerUsername = :CustomerUsername
		OR Email = :Email";
		
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":CustomerUsername" => $_POST['CustomerUsername'], ":Email" => $_POST['Email']));
		
		$rec = $stmt -> fetch();
		
		if (!empty($rec))
		{
			$taken=true;
		}
		else
		{
			//insert new customer
			$sql = "INSERT INTO CUSTOMER(CustomerUsername, LastName, FirstName, Email, encryptedPassword, Phone)
					VALUES
					(:CustomerUsername, :LastName, :FirstName, :Email, :encryptedPassword, :Phone)";
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute(array(":CustomerUsername"=>$_POST['CustomerUsername'],":LastName"=>$_POST['LastName'],":FirstName"=>$_POST['FirstName'],
					":Email"=>$_POST['Email'],":encryptedPassword"=> hash('sha1',$_POST['password']),":Phone" =>$phone));
			$newUsername = $dbConn->lastInsertId();
			header("Location: login.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<style>
		body {
			background: #A8EEFF;
			font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
		}
		.phone3{
			width: 30px;
			font-size:14px;
			text-align:center;
			padding:5px;
		}
		.phone4{
			width: 40px;
			font-size:14px;
			text-align:center;
			padding:5px;
		}
		.longInput{
			width:200px;
			font-size:14px;
			text-align:left;
			padding:5px;
		}
		td{
			padding:10px;
		}
		.Button, button{
			width:70px;
			height:25px;
			font-size:14px;
		}
		.Submit, .Reset, button {
			color: white;
  		  	margin: 8px 0;
 		    border: none;
  	   	    cursor: pointer;
		}
		.ResetCell{
			text-align:right;
		}
		.Submit {
			background-color: #00CD97;
		}
		.Reset {
			background-color: #f44336;
		}
		button {
			background-color: #00BFEC;
		}
		.required::before {
    		content: "* ";
		}
		.logo {
		    height: auto; 
		    width: auto; 
		    max-width: 100px; 
		    max-height: 100px;
		}
	</style>
</head>
<body>
	<center>
	<form method="post">
		<h1>Abalone Store</h1>
		<div class="storelogo">
   			<img src="logonotext.png" alt="Logo" class="logo">
  		</div>
		<h2>Create an account</h2>
		<?php
			if($retry&&!$passwordMatch)
			{
				echo'<h4>Passwords do not match.</h4>';
			}
			if($taken)
			{
				echo'<h4>Email or username already exists.</h4>';
			}
		?>
		<p class="required"> Required fields</p>
		<table>
		<tr>
		<td><p class="required">First Name: </p></td><td><input class="longInput" type='text' name='FirstName' placeholder='First Name' <?if($retry) echo'value="'.$_POST["FirstName"].'"'?> maxLength='25' required></td>
		<td><p class="required">Last Name: </td><td><input class="longInput" type='text' name='LastName' placeholder='Last Name' <?if($retry) echo'value="'.$_POST["LastName"].'"'?> maxLength='25' required></td>
		</tr>
		<tr>
		<td><p class="required">Username: </td><td><input class="longInput" type='text' name='CustomerUsername' <?if($retry) echo'value="'.$_POST["CustomerUsername"].'"'?> minLength='5' maxLength='20' placeholder='Username' required></td>
		<td><p class="required">Email: </td><td><input class="longInput" type='email' name='Email' <?if($retry) echo'value="'.$_POST["Email"].'"'?> placeholder='Email' maxLength='100' required></td>
		</tr>
		<tr>
		<td><p class="required">Phone: </td><td colspan="3">(<input type='text' class="phone3" name='Phone1' <?if($retry) echo'value="'.$_POST["Phone1"].'"'?> placeholder='555' minLength='3' maxLength='3' size='1' required>)
		<input type='text' class="phone3" name='Phone2' <?if($retry) echo'value="'.$_POST["Phone2"].'"'?> placeholder='555' minLength='3' maxLength='3' size='1' required>
		-<input type='text' class="phone4" name='Phone3' <?if($retry) echo'value="'.$_POST["Phone3"].'"'?> placeholder='5555' minLength='4' maxLength='4' size='2' required></td>
		</tr>
		<tr>
		<td><p class="required">Password: </td><td><input class="longInput" type='password' name='password' placeholder='Password' maxLength='25' required></td>
		<td><p class="required">Confirm Password: </td><td><input class="longInput" type='password' name='confirmPassword' placeholder='Confirm Password' maxLength='25' required></td>
		</tr>
		<tr>
		<td colspan="2"><input class="Button Submit" type="submit" value="submit"></td>
		<td colspan="2" class='ResetCell'><input class="Button Reset" type="reset" value="reset"></form></td>
		</tr>
		</table>
	<br>
	<form method="post" action="login.php"><button name='cart' type='login'>login</button></form>
	</center>
</body>
</html>
</html>