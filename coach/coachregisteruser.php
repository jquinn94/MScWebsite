<?php
session_start();
include("../connections/conn.php");

//checking if user has logged in, if not back to index page
if(!isset($_SESSION['authlogin'])){
  header('location:../index.php');
  exit();
}

//checking the user that has logged in is a coach and not a trainee
if(!isset($_SESSION['coachcheck'])){
    header('location:../trainee/trainee.php');
    exit();
}


$ifinputnotfilledout = "";
    
    //making sure none of the fields are empty 
    if(!empty($_POST["firstnameinput"]) && !empty($_POST["surnameinput"]) && !empty($_POST["datepicker"])
        && !empty($_POST["genderinput"]) && !empty($_POST["emailinput"]) && !empty($_POST["passinput"])
        && !empty($_POST["firstlineaddressinput"]) && !empty($_POST["secondlineaddressinput"]) && !empty($_POST["postcodeinput"])
        && !empty($_POST["countryinput"]) && !empty($_POST['phoneinput'])){
            
            $firstname = $conn->real_escape_string($_POST["firstnameinput"]);
            $surname = $conn->real_escape_string($_POST["surnameinput"]);
            $dob_old = $conn->real_escape_string($_POST["datepicker"]);
            
            //changing date format so database can store it
            $date = str_replace('/', '-', $dob_old);
            $newDate = date("Y-m-d", strtotime($date));
            
            $gender = $conn->real_escape_string($_POST["genderinput"]);
            $email = $conn->real_escape_string($_POST["emailinput"]);
            $phonenumber = $conn->real_escape_string($_POST['phoneinput']);
            $pass = $conn->real_escape_string($_POST["passinput"]);
            $address1 = $conn->real_escape_string($_POST["firstlineaddressinput"]);
            $address2 = $conn->real_escape_string($_POST["secondlineaddressinput"]);
            $postcode = $conn->real_escape_string($_POST["postcodeinput"]);
            $country = $conn->real_escape_string($_POST["countryinput"]);
            
            //checking the password contains one lowercase letter, one uppercase letter and one number
            if (preg_match('/[A-Z]/', $pass) && preg_match('/[0-9]/', $pass) && preg_match('/[a-z]/', $pass))
            {
                //checking the phone number is numeric
                if(is_numeric($phonenumber)){

                    $registertype = $conn->real_escape_string($_POST["registertype"]);
                    if($registertype == "Trainee"){
                        $registertypefordb = 0;
                    }else if($registertype == "Coach"){
                        $registertypefordb = 1;
                    }

                    $sqlcommand = 
                    "
                    INSERT INTO PT_users (first_name, last_name, email, pass, dob, gender, phone_number, coach_boolean) 
                    VALUES ('$firstname', '$surname', '$email', (AES_ENCRYPT('$pass','mykey')), '$newDate', '$gender', '$phonenumber', '$registertypefordb');
                    SET @last_id = LAST_INSERT_ID();

                    INSERT INTO PT_address (users_id, address_first_line , address_second_line, address_postcode, address_country) 
                    VALUES (@last_id, '$address1', '$address2', '$postcode', '$country');
                    ";

                    $result = $conn->multi_query($sqlcommand);

                    if(!$result){
                        $errormsg = $conn->error;

                        if (strpos($errormsg, "Duplicate entry '$email' for key 'email'") !== false) {
                            $ifinputnotfilledout = "*Email has already been used to sign up";
                        }else
                            echo $errormsg;
                    } else{
                        //send email confirmation of sign up
                        // the message
                        $msg = "You have signed up for GymShark Fitness!!\nWelcome aboard, you can now sign in and contact coaches!";

                        // use wordwrap() if lines are longer than 70 characters
                        $msg = wordwrap($msg,70);

                        // send email
                        mail($email,"Sign up confirmation",$msg);
                        header('location:viewclients.php');
                        exit();
                    }
                }else{
                    $ifinputnotfilledout = "*Phone number must be a number";
                }
            }else{
                $ifinputnotfilledout = "*Password must contain an uppercase letter, lowercase letter and a number";
            }
                            
            
    } else{
        if(isset($_POST['submitbutton'])){
            $ifinputnotfilledout = "* Not all fields filled out correctly";
        }
        
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register user</title>
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
    <link href="../styles/mycss.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
          $( "#datepicker" ).datepicker();
          $( "#datepicker" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
        } );
        </script>
</head>

<body>

    <div id="backgroundimagesignup">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <a class="navbar-brand" href="coach.php">
                <img src="../imgs/logo1.jpg" width="35" height="30" class="d-inline-block align-top" alt="">
                GymShark Fitness
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="viewclients.php">Clients</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="editwebsite.php">Edit Website</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="viewmessages.php">View Messages</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../calendar/coachcalendar.php">View Calendar</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="uploadprograms.php">Upload Fitness Programs</a>
                    </li>
                    
                </ul>

                <form method='POST' action='../logout.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Log Out</button>
                </form>

            </div>
        </nav>

        <div id="firstrowsignup" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                <form method='POST' action="coachregisteruser.php">
                    <?php echo "<p class='text-danger'>$ifinputnotfilledout </p>"?>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="text-white">Registration for: </label>
                        <select class="form-control" name="registertype">
                            <option>Trainee</option>
                            <option>Coach</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">First Name</label>
                        <input type="text" class="form-control mr-sm-2"  placeholder="First Name" 
                            name="firstnameinput">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Surname</label>
                        <input type="text" class="form-control" name="surnameinput"
                            placeholder="Surname">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Date of Birth</label>
                        <input type="text" class="form-control" name="datepicker" id="datepicker"
                        placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="text-white">Gender</label>
                        <select class="form-control" name="genderinput">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Email address</label>
                        <input type="email" class="form-control" name="emailinput"
                            placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Phone number</label>
                        <input type="text" class="form-control" name="phoneinput"
                            placeholder="Phone number">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Create Password</label>
                        <input type="password" class="form-control" name="passinput"
                            placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">First line of address</label>
                        <input type="text" class="form-control" name="firstlineaddressinput"
                            placeholder="Address line one">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Second line of address</label>
                        <input type="text" class="form-control" name="secondlineaddressinput"
                            placeholder="Address line two">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Post Code</label>
                        <input type="text" class="form-control" name="postcodeinput"
                            placeholder="Post Code">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Country</label>
                        <input type="text" class="form-control" name="countryinput"
                            placeholder="Country">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-info" name="submitbutton">Submit</button>
                    </div>
                </form>

            </div>
            <div class="col-sm-5"></div>
        </div>

    </div>


</body>

</html>