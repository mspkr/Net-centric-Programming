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
      if(isset($_POST['completedevent'])){
        include 'db.php';
        $eventid = $_POST['completedevent'];
        $sql = "UPDATE todo SET `status` = 'Y' WHERE email = '$email' AND eventid = '$eventid'";
        if($conn->query($sql) === TRUE){
          header("Refresh:0");
          $conn->close();
        }
      }elseif (isset($_POST['putback'])){
        include 'db.php';
        $eventid = $_POST['putback'];
        $sql = "UPDATE todo SET `status` = 'N' WHERE email = '$email' AND eventid = '$eventid'";
        if($conn->query($sql) === TRUE){
          header("Refresh:0");
          $conn->close();
        }
      }elseif (isset($_POST['deleteevent'])){
        include 'db.php';
        $eventid = $_POST['deleteevent'];
        $sql = "DELETE FROM todo WHERE eventid='$eventid' AND email ='$email'";
        if($conn->query($sql) === TRUE){
          header("Refresh:0");
          $conn->close();
        }
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>ToDo</title>
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
      <a class="navbar-brand" href="#"><?php echo date("d-m-Y");?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="addevent.php">ADD to list</a></li>
        <li><a href="#">ToDo</a></li>
        <li><a href="#completed">Completed</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><?php echo 'Welcome back '.$username;?></a></li>
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
              <!--Task to be done-->
              <div class="col-sm-8"  style="background-color:#E0E8F0;">
                <h3>ToDo</h3>
                <?php
                  include "db.php";
                  $sql = "SELECT * FROM todo WHERE email='$email' AND status = 'N' AND date='$date'";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                          echo '<blockquote>';
                            echo '<div class="panel panel-default">';
                              echo '<div class="panel-heading"><form action="" method="POST">'.$row["title"];
                                    echo '<button class="btn btn-default pull-right" type="submit" name="completedevent" value="'.$row["eventid"].'">';
                                        echo '<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>';
                                    echo '</button>';
                                    echo '<button class="btn btn-default pull-right" type="submit" name="deleteevent" value="'.$row["eventid"].'">';
                                        echo '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
                                    echo '</button>';
                            echo '</form></div>';
                            echo '<div class="panel-body">'.$row["description"].'</div>';
                            echo '</div>';
                          echo '</blockquote>';
                      }
                  } 
                  else{
                          echo '<blockquote>';
                          echo '<div class="panel panel-default">';
                            echo '<div class="panel-heading">'."You have completed all works".'</div>';
                          echo '</div>';
                          echo '</blockquote>';
                  }
                  $conn->close();
                  ?>
              </div>
              <!--end of Task to be done-->
              <!--Task completed-->
              <div class="col-sm-4" id="completed" style="background-color:#C0C0C0;">
                <h3>Completed</h3>
                  <?php
                    include "db.php";
                    $sql = "SELECT * FROM todo WHERE email='$email' AND status = 'Y' AND date='$date'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                            echo '<blockquote>';
                              //collapse code start
                                    echo '<div class="panel-group">';
                                      echo '<div class="panel panel-default">';
                                        echo '<div class="panel-heading">';
                                          echo '<h4 class="panel-title">';
                                            echo '<a data-toggle="collapse" href="#'.$row["eventid"].'">'.$row["title"].'</a>';
                                          echo '</h4>';
                                        echo '</div>';
                                        echo '<div id="'.$row["eventid"].'" class="panel-collapse collapse">';
                                          echo '<ul class="list-group">';
                                            echo '<li class="list-group-item">'.$row["description"].'</li>';
                                          echo '</ul>';
                                          echo '<div class="panel-footer" style="height: 50px"><form action="" method="POST">';
                                              echo '<button class="btn btn-default pull-right" type="submit" name="putback" value="'.$row["eventid"].'">';
                                                  echo '<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>';
                                              echo '</button>';
                                              echo '<button class="btn btn-default pull-right" type="submit" name="deleteevent" value="'.$row["eventid"].'">';
                                                  echo '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
                                              echo '</button>';
                                          echo '</form></div>';
                                        echo '</div>';
                                      echo '</div>';
                                    echo '</div>';
                              //collapse code end
                            echo '</blockquote>';
                      }
                  }
                ?>
              </div>
              <!--end of task completed-->
          </div>
        </div>
      </div>   
  </div>

  </body>
  </html>