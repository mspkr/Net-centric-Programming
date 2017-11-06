<?php
	if(isset($_SESSION)){
		header("Location: home.php");		
	}else{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    //login request using post method
		    if (isset($_POST['login-submit'])) {
		    	$email = $_POST["email"];
		    	$password = $_POST["password"];
		    	if (empty($email)){
				    $message = "Email required";
				}elseif (empty($password)){
					$message = "Password required";
				}else{
					include "db.php";
					$sql = "SELECT email,name FROM login WHERE email='$email' AND password='$password'";
					session_start();
					$result = $conn->query($sql);
					if ($result->num_rows == 1 ) {
	                    $row = $result->fetch_assoc();
						$_SESSION['email']=$row["email"];
						$_SESSION['username']=$row["name"];
						header("Location: home.php");
						exit();
					}else{
						session_destroy();
						$message = "Invalid Email or Password";
					}
				}
		    }
		    //register method using post method
		    elseif (isset($_POST['register-submit'])) {
		    		$name = $_POST["name"];
		    		$email = $_POST["email"];
		    		$password = $_POST["password"];
		    	//name validation
		    	if (empty($name)) {
				    $message = "Name is required";
				    $foundErr = "Yes"; 
				}else {
				    // check if name only contains letters and whitespace
				    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				      $message = "Name must have Only letters and white space ";
				      $foundErr = "Yes"; 
				    }
				}
				//email validation
				if (empty($email)) {
				    $message = "Email is required";
				    $foundErr = "Yes";
				} else {
				    // check if e-mail address is well-formed
				    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				      $message = "Invalid email format";
				      $foundErr = "Yes"; 
					}
				}
				//password validation
				if (empty($password)) {
				    $message = "password is required";
				    $foundErr = "Yes";
				} else {
				    // check if password has 1-Lowercae, 1-Uppercase, Symbol and lenght of 8 to 12
				    if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/',$password)){
				      $message = "Password of lenght 8-20 with at least one lowercase char, one uppercase char, one digit, one special sign of @#-_$%^&+=§!? is aceepted.";
				      $foundErr = "Yes"; 
					}
				}
				if($foundErr!="Yes"){
					include "db.php";
				    $check="SELECT * FROM login WHERE Email = '$email'";
				    $result = $conn->query($check);
				    //check if email already exist in database.
                  	if ($result->num_rows > 0) {
                  		$message = "Email already exists";
                  		$conn->close();
				    }else{
				    	//insert new user details in database.
				    	$sql = "INSERT INTO `login`(`email`,`password`,`name`) VALUES ('$email','$password','$name')";
				    	if ($conn->query($sql) === TRUE) {
						    $message="Account created successfully, Please Login";
						} else {
						    $message="Error: " . $sql . " " . $conn->error;
						}
						$conn->close();
				    }
				}
				//end of registration
		    }
		    //end of post method
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Todo</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
     <script type="text/javascript" src="main.js"></script>
</head>
<body>
	<div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="#" id="register-form-link">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<!--login form-->
								<form id="login-form" action="" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="" autocomplete="off">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" autocomplete="off">
									</div>
									<div class="form-group text-center">
										<label for="remember"><?php echo $message;?></label>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
								</form>

								<!--register form-->
								<form id="register-form" action="" method="post" role="form" style="display: none;">
									<div class="form-group">
										<input type="text" name="name" id="name" tabindex="1" class="form-control" placeholder="Name" autocomplete="off"><?php echo $nameErr;?>
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="2" class="form-control" placeholder="Email Address" value="" autocomplete="off">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="3" class="form-control" placeholder="Password" autocomplete="off">
									</div>
									<div class="form-group text-center">
										<label for="remember"><?php echo $message;?></label>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>