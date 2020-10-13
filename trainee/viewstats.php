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

//getting the users info
$iddata = $_SESSION['authlogin'];
    
    $getinfo = "SELECT * FROM PT_users WHERE email='$iddata'";
    
    $result = $conn->query($getinfo);

    if(!$result){
        echo $conn->error;
    } else{
        
        while($row=$result->fetch_assoc()){
            $firstnameofclient = $row['first_name'];
            $lastnameofclient = $row['last_name'];
            $profilepic = $row['profile_pic'];
            $idofuser = $row['users_id'];
        }

    }

    $arrayofsquatresults = array();
    $arrayofdeadliftresults = array();
    $arrayofbenchresults = array();

    //squat results
    $getsquatinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$idofuser' AND exercise_type='2' ORDER BY id ASC";

    $resultsquat = $conn->query($getsquatinfo);

    if(!$resultsquat){
        $conn->error;
        exit;
    }else{

        while($row=$resultsquat->fetch_assoc()){
            $result = $row['result'];
            array_push($arrayofsquatresults, $result);
        }
    }

    //deadlift results
    $getdeadliftinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$idofuser' AND exercise_type='3' ORDER BY id ASC";

    $resultdeadlift = $conn->query($getdeadliftinfo);

    if(!$resultdeadlift){
        $conn->error;
        exit;
    }else{

        while($row=$resultdeadlift->fetch_assoc()){
            $result = $row['result'];
            array_push($arrayofdeadliftresults, $result);
        }
    }


    //bench results
    $getbenchinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$idofuser' AND exercise_type='1' ORDER BY id ASC";

    $resultbench = $conn->query($getbenchinfo);

    if(!$resultbench){
        $conn->error;
        exit;
    }else{

        while($row=$resultbench->fetch_assoc()){
            $result = $row['result'];
            array_push($arrayofbenchresults, $result);
        }
    }

    $loopsize = count($arrayofsquatresults);

?>
<!DOCTYPE html>
<html>

<head>
    <title>View stats</title>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link href="../styles/mycss.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<script>
    document.addEventListener('DOMContentLoaded', function () {

            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        <?php 
                            for($loop=1; $loop<=$loopsize;$loop++){   
                                echo "'Week $loop', ";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Chest press',
                        data: [
                        <?php 
                            for($loop=0; $loop<$loopsize;$loop++){
                                echo $arrayofbenchresults[$loop].", ";
                            }   
                        ?>
                        ],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)'
                    },
                    {
                        label: 'Squats',
                        data: [                        
                        <?php 
                            for($loop=0; $loop<$loopsize;$loop++){
                                echo $arrayofsquatresults[$loop].", ";
                            }   
                        ?>
                        ],
                        borderColor: 'rgba(255, 0, 0, 1)',
                        backgroundColor: 'rgba(255, 0, 0, 0.2)'
                    },
                    {
                        label: 'Deadlifts',
                        data: [                        
                        <?php 
                            for($loop=0; $loop<$loopsize;$loop++){
                                echo $arrayofdeadliftresults[$loop].", ";
                            }   
                        ?>
                        ],
                        borderColor: 'rgba(0, 255, 0, 1)',
                        backgroundColor: 'rgba(0, 255, 0, 0.2)'
                    }]
                },
                options: { 
                    legend: {
                        labels: {
                            fontColor: "white",
                            fontSize: 18
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                fontColor: "white",
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: "white",
                            }
                        }]
                    }
                },
            });

            myChart.render();

    });

</script>
<body>

    <div id="backgroundimages2">
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
                        <a class="nav-link" href="viewmessages.php">View Messages</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../calendar/clientcalendar.php">View Calendar</a>
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

        <div id="firstrowtrainee" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h1 id="mainwords" class="font-weight-bold font-italic text-white">
                    <?php echo "$firstnameofclient $lastnameofclient"; ?>
                </h1>
                <p><?php echo "<img src='$profilepic' width='150' height='150' alt=''>"; ?></p>

            
            </div>
            <div class="col-sm-1"></div>
        </div>


        <div class="row pt-5">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <h4 id="mainwords" class="font-weight-bold font-italic text-white">Weekly Strength Progression:</h4>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="col-sm-1"></div>
        </div>



</body>

</html>