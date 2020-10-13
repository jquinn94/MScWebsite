<?php
    include("connections/conn.php");          
    session_start();

    //getting the website content saved in the database to be able to display on the home page
    $titlequery = "SELECT * FROM PT_websitecontent WHERE id='3'";
    $descriptionquery = "SELECT * FROM PT_websitecontent WHERE id='4'";

    $result1 = $conn->query($titlequery);

    if(!$result1){
        echo $conn->error;
    }else{
        $row=$result1->fetch_assoc();
        $title = $row['content_description'];
    }

    $result2 = $conn->query($descriptionquery);

    if(!$result2){
        echo $conn->error;
    }else{
        $row=$result2->fetch_assoc();
        $description = $row['content_description'];
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>About us</title>
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

<body>

    <div id="backgroundimageaboutus">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <a class="navbar-brand" href="index.php">
                <img src="imgs/logo1.jpg" width="35" height="30" class="d-inline-block align-top" alt="">
                GymShark Fitness
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
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
                    
                <form method='POST' action='aboutus.php' class="form-inline">
                    <input class="form-control mr-sm-2" type="text" placeholder="Email" name="emailinput">
                    <input class="form-control mr-sm-2" type="password" placeholder="Password" name="passwordinput">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Login</button>
                </form>
                
            </div>
        </nav>

        <?php

//the login section
if(isset($_POST['emailinput'])){
  
    $username = $conn->real_escape_string($_POST['emailinput']);
    $passw = $conn->real_escape_string($_POST['passwordinput']);

    //passwords saved in the database are encrypted 
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


        <div id="firstrow" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h4 class='font-weight-bold text-center text-white font-italic'><?php echo $title ?></h4>
                <p class='font-weight-bold text-center text-white font-italic'><?php echo $description ?></p>
            </div>
            <div class="col-sm-1"></div>
        </div>

        <div id="firstrowaboutus" class="row">
            <div class="col-sm-5">

            </div>
        </div>




        
        


    </div>


</body>

</html>