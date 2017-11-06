<?php
    session_start();
    if(empty($_SESSION["email"])){
        header("Location: index.php");
        exit();
    }
    else{
      $username = $_SESSION["username"];
      $email = $_SESSION["email"];
      $date = date("Y-m-d");
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $title = $_POST['title'];
        $sentdate  = date("Y-m-d",strtotime($_POST['date']));
        $description = $_POST['description'];
        if (empty($title)) {
            $message = "Title is required";
            $foundErr = "Yes"; 
        }
        if (empty($sentdate)) {
            $message = "please select date";
            $foundErr = "Yes"; 
        }else {
            if($sentdate<date("Y-m-d")){
              $message = "please select date >= ".date("d/m/Y");
              $foundErr = "Yes";
            }
        } 
        if (empty($description)) {
            $message = "Short description is required";
            $foundErr = "Yes"; 
        }
        if($foundErr !== "Yes"){
            include "db.php";
            $sql = "INSERT INTO `todo`(`email`,`title`,`description`,`date`) VALUES ('$email','$title','$description','$date')";
            if ($conn->query($sql) === TRUE) {
                $message="Event added to calender";
            } else {
                $message="Error: " . $sql . " " . $conn->error;
            }
            $conn->close();
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

</head>
<body style='background-image:url("bg.jpg");background-attachment: fixed;'>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="home.php#"><?php echo date("d-m-Y");?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="#">ADD to list</a></li>
        <li><a href="home.php#">ToDo</a></li>
        <li><a href="home.php#completed">Completed</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="home.php#"><?php echo 'Welcome back '.$username;?></a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>


  <div class="container" >
      <br><br><br>
      <!--todo list code-->
      <div class="jumbotron" style="background-color: rgba(90, 90, 90, 0.3);">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12"  style="background-color:#E0E8F0;">
                      <form class="form-horizontal" action="" method="post">
                          <fieldset>
                            <legend class="text-center">New Event</legend>
                    
                            <!-- Event Title -->
                            <div class="form-group">
                              <label class="col-md-3 control-label" for="title">Title</label>
                              <div class="col-md-9">
                                <input id="title" name="title" type="text" placeholder="Title of event" class="form-control">
                              </div>
                            </div>
                    
                            <!-- Event Date -->
                            <div class="form-group">
                              <label class="col-md-3 control-label" for="date">Date</label>
                              <div class="col-md-9">
                                <input class="form-control" id="date" name="date" type="date">
                              </div>
                            </div>
                    
                            <!-- Description -->
                            <div class="form-group">
                              <label class="col-md-3 control-label" for="description">Description</label>
                              <div class="col-md-9">
                                <textarea class="form-control" id="description" name="description" placeholder="Please Describe about event here..." rows="5"></textarea>
                              </div>
                            </div>
                    
                            <!-- Form actions -->
                            <div class="form-group">
                              <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                              </div>
                            </div>
                          </fieldset>
                      </form>
                      <?php echo $message;?>
                </div>
            </div>
        </div>
      </div>
  </div>
</body>
</html>