<?php
session_start();
include("connections/conn.php");

    //getting the coach id and proctecting against URL injection
    if(isset($_GET['id'])){
        $iddata = $conn->real_escape_string($_GET['id']);
    }else{
        header('Location: index.php');
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
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

    <div id="backgroundimages2" >
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

                <form method='POST' action='viewcoach.php' class="form-inline">
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
        
        <?php

            $getinfo = "SELECT * FROM PT_users WHERE users_id='$iddata'";
            
            $result = $conn->query($getinfo);

            if(!$result){
                echo $conn->error;
            } else{
                
                while($row=$result->fetch_assoc()){
                    $firstname = $row['first_name'];
                    $lastname = $row['last_name'];
                    $dob_old = $row['dob'];
                    $date = str_replace('/', '-', $dob_old);
                    $dob = date("d-m-Y", strtotime($date));
                    $gender = $row['gender'];
                    $email = $row['email'];
                    $phonenumber = $row['phone_number'];
                    $profilepic = $row['profile_pic'];
                    $profilepic2 = str_replace("../", "", "$profilepic");
                    $users_id = $row['users_id'];
                }
    
                $getaddressinfo = "SELECT * FROM PT_address WHERE users_id='$users_id'";

                $result2 = $conn->query($getaddressinfo);

                if(!$result2){
                    echo $conn->error;
                } else{
                    while($row=$result2->fetch_assoc()){
                        $address1 = $row['address_first_line'];
                        $address2 = $row['address_second_line'];
                        $postcode = $row['address_postcode'];
                        $country = $row['address_country'];
                    }


                }

            }

 
        ?>


        <div id="firstrowtrainee" class="row pb-5">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h1 id="mainwords" class="font-weight-bold font-italic text-white mt-5">
                    <?php echo "$firstname $lastname"; ?>
                </h1>
                <p><?php echo "<img src='$profilepic2' width='150' height='150' alt=''>"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Date of birth: $dob"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Gender: $gender"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Email: $email"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Phone number: $phonenumber"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Address line one: $address1"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Address line two: $address2"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Postcode: $postcode"; ?></p>
                <p class="font-weight-bold font-italic text-white"><?php echo "Country: $country"; ?></p>
            </div>
            <div class="col-sm-1"></div>
        </div>

    </div>


</body>

</html>