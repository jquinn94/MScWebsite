<?php
session_start();
include("../connections/conn.php");

//checking if user has logged in, if not back to index page
if(!isset($_SESSION['authlogin'])){
    header('location:../index.php');
    exit;
  }
  
  //checking the user that has logged in is a trainee and not a coach
  if(!isset($_SESSION['traineecheck'])){
      header('location:../coach/coach.php');
  }

  //if reply button has been pressed
  if(isset($_POST['replybtn'])){

    //getting group number
    $value = $_POST['groupnumber'];
    $arrayofids = array();

    $querytogetgroupmembers = "SELECT * FROM PT_group WHERE group_number='$value'";

    $result = $conn->query($querytogetgroupmembers);

    if(!$result){
        echo $conn->error;
    }else{

        //adding all clients in the group to an array
        while($row=$result->fetch_assoc()){
            $idtopush = $row['users_id'];
            array_push($arrayofids, $idtopush);
        }

        //setting the array as a session variable and also setting the group number as session variable
        $_SESSION['array_of_clients'] = $arrayofids;
        $_SESSION['group_message'] = $value;
        header('location: groupmessagereply.php');
        exit();

    }

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

        //query to get users id 
        $email = $_SESSION['authlogin'];
        $getclientid = "SELECT * FROM PT_users WHERE email='$email'";
        $result = $conn->query($getclientid);
        
        //arrays to store groups first and last names in
        $arrayoffirstnames = array();
        $arrayoflastnames = array();
        
        if(!$result){
            echo $conn->error;
        }else{
            $row = $result->fetch_assoc();
            $clientid = $row['users_id'];
        }
        
        //query to get all the group messages for this user
        $getmessagesquery = "SELECT * FROM PT_group
                                LEFT OUTER JOIN 
                                PT_group_messages ON
                                PT_group_messages.group_number = PT_group.group_number
                                LEFT OUTER JOIN
                                PT_message_content ON
                                PT_message_content.content_id = PT_group_messages.message_content
                                LEFT OUTER JOIN
                                PT_users ON
                                PT_group_messages.group_message_from = PT_users.users_id
                                WHERE PT_group.users_id = '$clientid'
                                ORDER BY PT_group_messages.id DESC";

        $result2 = $conn->query($getmessagesquery);

        if(!$result2){
            echo $conn->error;
        }else{

            $arrayofmessagecontents = array();
            $arrayofmessagefromfirstnames = array();
            $arrayofmessagefromlastnames = array();
            $arrayofcoachcheck = array();
            $arrayofdates = array();
            $arrayoftimes = array();

            while($row = $result2->fetch_assoc()){
                //saving all message contents, who they are from and the dates of messages
                array_push($arrayofmessagecontents, $row['content']);
                array_push($arrayofmessagefromfirstnames, $row['first_name']);
                array_push($arrayofmessagefromlastnames, $row['last_name']);
                array_push($arrayofcoachcheck, $row['coach_boolean']);
                array_push($arrayofdates, $row['date']);
                array_push($arrayoftimes, $row['time']);

                $groupnumber = $row['group_number'];
            }

            $loopsizeouter = count($arrayofmessagecontents);

            //checking if person is in a group or not
            if(!empty($groupnumber)){
                $getgroupinfo = "SELECT * FROM PT_group 
                                LEFT OUTER JOIN 
                                PT_users ON 
                                PT_users.users_id = PT_group.users_id
                                WHERE
                                PT_group.group_number = '$groupnumber'";


            }else{

                $getgroupinfo = "SELECT * FROM PT_group 
                                LEFT OUTER JOIN 
                                PT_users ON 
                                PT_users.users_id = PT_group.users_id
                                WHERE
                                PT_group.group_number = '0'";
            }
            
            
            $result3 = $conn->query($getgroupinfo);

        }

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

        if(!$result3){
            echo $conn->error;
        }else{

            //if person is not in a group
            if(!isset($groupnumber)){
                echo "
                <div class='row mt-5'>
                    <div class = 'col-2'></div>
                    <div class = 'col-8'>
                        <h3 class='font-weight-bold text-white'>You have no messages</h3>
                    </div>
                    <div class = 'col-2'></div>
                </div>";
            }else{
                //if person is in a group then get all of the groups first and last names
                while($row = $result3->fetch_assoc()){

                    array_push($arrayoffirstnames, $row['first_name']);
                    array_push($arrayoflastnames, $row['last_name']);
                
                }

                $loopsize = count($arrayoffirstnames);

                //displaying all the group messages that the user has 
                for($loopouter = 0; $loopouter < $loopsizeouter; $loopouter++){

                    $coachvariable = "";

                    if($arrayofcoachcheck[$loopouter] == 1){
                        $coachvariable = "(PT)";
                    }

                    echo "
                    <div class='row'>
                        <div class = 'col-2'></div>
                        <div class = 'col-8'>
                            <div class='card text-white bg-dark mt-3'>
                                <div class='card-header font-weight-bold'>
                                    Group $groupnumber members: <br>";  
                                    for($loop = 0; $loop < $loopsize; $loop++){
                                        echo "$arrayoffirstnames[$loop] $arrayoflastnames[$loop] <br>";
                                    }
                                echo"<br>Message from: $arrayofmessagefromfirstnames[$loopouter] $arrayofmessagefromlastnames[$loopouter] $coachvariable <br> Date: $arrayofdates[$loopouter] <br> Time: $arrayoftimes[$loopouter]</div>
                                <div class='card-body'>
                                    <p class='card-text'>$arrayofmessagecontents[$loopouter]</p>
                                    <form method='POST'>
                                        <input type='hidden' name='groupnumber' value='$groupnumber'>
                                        <button class='btn btn-primary' name='replybtn' type='submit'>Reply</button>
                                    </form>
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