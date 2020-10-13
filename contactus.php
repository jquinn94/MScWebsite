<?php
    include("connections/conn.php");          
    session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Contact us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <link href="styles/mycss.css" rel="stylesheet">
</head>

<?php
    //making sure all inputs are filled out
    if((isset($_POST['submitbtn'])) && (!empty($_POST['message_input'])) && (!empty($_POST['name_input'])) && (!empty($_POST['email_input']))){

        //proctection from SQL injection
        $name = $conn->real_escape_string($_POST['name_input']);
        $email = $conn->real_escape_string($_POST['email_input']);
        $message = $conn->real_escape_string($_POST['message_input']);
        date_default_timezone_set('Europe/Dublin');
        $date = date("l jS M Y");
        $time = date("H:i:s");

        $sqlinsertquery = "INSERT INTO PT_external_messages (email_from, name_from, message_content, date, time) 
        VALUES ('$email', '$name', '$message', '$date', '$time')";

        $result = $conn->query($sqlinsertquery);

        if(!$result){
            echo $conn->error;
        }else{
            header("location:index.php");
        }


    }




?>



<body>

    <div id="backgroundimageaboutus">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <a class="navbar-brand" href="index.php">
                <img src="imgs/logo1.jpg" width="35" height="30" class="d-inline-block align-top" alt="">
                GymShark Fitness
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php">About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Personal Trainers
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                            $coaches = "SELECT * FROM PT_users WHERE coach_boolean='1'";
                            $coachresult = $conn->query($coaches);

                            while($row=$coachresult->fetch_assoc()){
                                $firstname = $row['first_name'];
                                $lastname = $row['last_name'];
                                $coachid = $row['users_id'];
                                echo "<a class='dropdown-item' href='viewcoach.php?id=$coachid'>$firstname $lastname</a>";
                            }

                            ?>
                        </div>
                    </li>
                </ul>

                <form method='POST' action='contactus.php' class="form-inline">
                    <input class="form-control mr-sm-2" type="text" placeholder="Email" name="emailinput">
                    <input class="form-control mr-sm-2" type="password" placeholder="Password" name="passwordinput">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Login</button>
                </form>

            </div>
        </nav>

        <?php

//login section
if(isset($_POST['emailinput'])){
  
//proctecting against SQL injection
  $username = $conn->real_escape_string($_POST['emailinput']);
  $passw = $conn->real_escape_string($_POST['passwordinput']);

  //password is encryted in the database 
  $auth = "SELECT * FROM PT_users WHERE email='$username' AND pass=(AES_ENCRYPT('$passw','mykey'))";

  $result = $conn->query($auth);

  if(!$result){
      echo $conn->error;
  } else{

      while($row=$result->fetch_assoc()){
          $coachortrainer = $row['coach_boolean'];
      }

      $numrows = $result->num_rows;

      if($numrows > 0){
          $_SESSION['authlogin'] = $username;
          
          if($coachortrainer == 1){
              //if coach set the coachcheck session variable to one and go to coach page
              $_SESSION['coachcheck'] = 1;
              header('location:coach/coach.php');
              exit;
          }else{
              //if trainee set the traineecheck session variable to one and go to trainee page
              $_SESSION['traineecheck'] = 1;
              header('location:trainee/trainee.php');
              exit;
          }
      }
  }

}
?>



        <div id="firstrowsignup" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                <form method='POST' action='contactus.php'>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Name</label>
                        <input type="test" class="form-control" name="name_input" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Email address</label>
                        <input type="email" class="form-control" name="email_input"
                            placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1" class="text-white">Message</label>
                        <textarea class="form-control" name="message_input" rows="5" placeholder="Message (max 255 characters)"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submitbtn" class="btn btn-outline-info">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-5"></div>
        </div>

    </div>


</body>

</html>