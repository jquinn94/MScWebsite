<?php
session_start();
include("../connections/conn.php");

//checking if user has logged in, if not back to index page
if(!isset($_SESSION['authlogin'])){
    header('location:../index.php');
    exit();
  }
  
    //checking the user that has logged in is a trainee and not a coach
  if(!isset($_SESSION['traineecheck'])){
      header('location:../coach/coach.php');
      exit();
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
    <link href="../styles/mycss.css" rel="stylesheet">
</head>

<body>

    <div id="backgroundimages3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <a class="navbar-brand" href="trainee.php">
                <img src="../imgs/logo1.jpg" width="35" height="30" class="d-inline-block align-top" alt="">
                GymShark Fitness
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

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

                    <li class="nav-item">
                        <a class="nav-link" href="../calendar/clientcalendar.php">View Calendar</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="viewstats.php">View Stats</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="viewprograms.php">View Programs</a>
                    </li>
                </ul>

                <form method='POST' action='../logout.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Log Out</button>
                </form>

            </div>
        </nav>

        
        <?php

        //get the users info from the db
        $email = $_SESSION['authlogin'];
        $getuserid = "SELECT * FROM PT_users WHERE email='$email'";
        $result = $conn->query($getuserid);
        
        if(!$result){
            echo $conn->error;
        }else{
            $row = $result->fetch_assoc();
            $userid = $row['users_id'];
        }
        
        //query to get all the messages for the user
        $getmessagesquery = "SELECT * FROM PT_internal_messages 
                            LEFT OUTER JOIN PT_users ON
                            PT_internal_messages.message_from = PT_users.users_id 
                            LEFT OUTER JOIN PT_message_content ON
                            PT_internal_messages.message_content = PT_message_content.content_id
                            WHERE
                            PT_internal_messages.message_to='$userid'
                            ORDER BY id DESC";

        $result2 = $conn->query($getmessagesquery);


        echo "
        <div class='row mt-5'>
            <div class = 'col-2'></div>
            <div class = 'col-8'>
                <nav aria-label='Page navigation example'>
                    <ul class='pagination'>
                        <li class='page-item'><a class='page-link' href='viewmessages.php'>Non Group Messages</a></li>
                        <li class='page-item'><a class='page-link' href='viewgroupmessages.php'>Group Messages</a></li>
                    </ul>
                </nav>
            </div>
            <div class = 'col-2'></div>
        </div>";

        if(!$result2){
            echo $conn->error;
        }else{

            $rowcount=mysqli_num_rows($result2);

            //if no messages for the user
            if($rowcount == 0){
                echo "
                <div class='row mt-5'>
                    <div class = 'col-2'></div>
                    <div class = 'col-8'>
                        <h3 class='font-weight-bold text-white'>You have no messages</h3>
                    </div>
                    <div class = 'col-2'></div>
                </div>";
            }else{
                //if there is messages for the user
                while($row = $result2->fetch_assoc()){
                
                    //get the info of the message and then display it below
                    $firstnameofclient = $row['first_name'];
                    $lastnameofclient = $row['last_name'];
                    $idofclient = $row['users_id'];
                    $messagecontent = $row['content'];
                    $date = $row['date'];
                    $time = $row['time'];
    
                    echo "
                    <div class='row'>
                        <div class = 'col-2'></div>
                        <div class = 'col-8'>
                            <div class='card text-white bg-dark mt-3'>
                                <div class='card-header font-weight-bold'>
                                    Message from: $firstnameofclient $lastnameofclient <br> Date: $date <br> Time: $time
                                </div>
                                <div class='card-body'>
                                    <p class='card-text'>$messagecontent</p>
                                    <a href='replymessage.php?clientid=$idofclient' class='btn btn-primary'>Reply</a>
                                </div>
                            </div>
                        </div>
                        <div class = 'col-2'></div>
                    </div>";
        
        
                }
            }
            
        }

        ?>

    </div>


</body>

</html>