<?php
    include("../connections/conn.php");          
    session_start();

    //checking if user has logged in, if not back to index page
    if(!isset($_SESSION['authlogin'])){
        header('location:../index.php');
        exit();
    }
      
    //checking the user that has logged in is a coach and not a trainee
    if(!isset($_SESSION['coachcheck'])){
        header('location:../index.php');
        exit();
    }else{
        if(isset($_GET['clientid'])){
          $clientiddata = $conn->real_escape_string($_GET['clientid']);
        }else{
            header('Location: coach.php');
            exit();
        }
    }


    $coachemail = $_SESSION['authlogin'];
    
    $traineeinfoquery = "SELECT * FROM PT_users WHERE users_id='$clientiddata'";
    $coachinfoquery = "SELECT * FROM PT_users WHERE email='$coachemail'";

    $result = $conn->query($traineeinfoquery);
    
    //getting client info from db
    if(!$result){
        echo $conn->error;
    }else{
        $row = $result->fetch_assoc();
        $traineefirstname = $row['first_name'];
        $traineelastname = $row['last_name'];
        $coachcheck = $row['coach_boolean'];
    }

    //if client doesn't exist or if tried to message another coach
    if((empty($traineefirstname)) || ($coachcheck == 1)){
        header('location: coach.php');
        exit();
    }

    //if submit button has been pressed and the message input box isn't empty
    if((isset($_POST['submitbtn'])) && (!empty($_POST['messageinput']))){
        //insert the message into all the appropriate tables in the db
        $message = $_POST['messageinput'];
        $message2=$conn->real_escape_string($message);
        date_default_timezone_set('Europe/Dublin');
        $date = date("l jS M Y");
        $time = date("H:i:s");

        $result2 = $conn->query($coachinfoquery);

        if(!$result2){
            echo $conn->error;
        }else{
            $row = $result2->fetch_assoc();
            $coachid = $row['users_id'];

            $sqlinsertmessage = 
            "
            INSERT INTO PT_message_content (content, date, time)
            VALUE ('$message2', '$date', '$time');
            SET @last_id = LAST_INSERT_ID();

            INSERT INTO PT_internal_messages (message_to, message_from, message_content)
            VALUES ('$clientiddata', '$coachid', @last_id);
            ";

            $result3 = $conn->multi_query($sqlinsertmessage);

            if(!$result3){
                echo $conn->error;
            }else{
                header("location: viewmessages.php");
                exit();
            }

        }

    }



?>
<!DOCTYPE html>
<html>

<head>
    <title>Reply message</title>
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
</head>

<body>

    <div id="backgroundimageaboutus">
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
                        <a class="nav-link" href="coachregisteruser.php">Register User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="viewclients.php">Clients</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="editwebsite.php">Edit Website</a>
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


        <div id="firstrowsignup" class="row mt-5">
            <div class="col-sm-1"></div>
            <div class="col-sm-6">
                <form method="POST" action=<?php echo "replymessage.php?clientid=$clientiddata"; ?>>
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="text-white">Message to:</label>
                        <p class="text-white font-weight-bold"><?php echo "$traineefirstname $traineelastname" ?></p>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1" class="text-white">Message</label>
                        <textarea class="form-control" name="messageinput" rows="5"
                            placeholder="Message (max 4000 characters)"></textarea>
                    </div>
                    <div class="form-group">
                        <button name="submitbtn" type="submit" class="btn btn-outline-info">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-5"></div>
        </div>

    </div>


</body>

</html>