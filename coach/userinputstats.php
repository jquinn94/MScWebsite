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

//checking that the user id has been set in the url
if(isset($_GET['userid'])){
    $iddata = $conn->real_escape_string($_GET['userid']);  
}else{
    header('Location: viewclients.php');
    exit();
}

//if the delete stats button has been pressed
if(isset($_POST['deletestatsbtn'])){

    //selecting the last stat input for the user for each exercise
    $latestbench = "SELECT MAX(PT_users_weeklyupdate.id) AS 'last_insert_id' FROM PT_users_weeklyupdate WHERE users_id = '$iddata' AND exercise_type ='1'";
    $latestsquat = "SELECT MAX(PT_users_weeklyupdate.id) AS 'last_insert_id' FROM PT_users_weeklyupdate WHERE users_id = '$iddata' AND exercise_type ='2'";
    $latestdeadlift = "SELECT MAX(PT_users_weeklyupdate.id) AS 'last_insert_id' FROM PT_users_weeklyupdate WHERE users_id = '$iddata' AND exercise_type ='3'";

    $result1 = $conn->query($latestbench);

    if(!$result1){
        echo $conn->error;
    }else{
        $row = $result1->fetch_assoc();
        $latestbenchid = $row['last_insert_id'];
        $result2 = $conn->query($latestsquat);

        if(!$result2){
            echo $conn->error;
        }else{
            $row = $result2->fetch_assoc();
            $latestsquatid = $row['last_insert_id'];
            $result3 = $conn->query($latestdeadlift);

            if(!$result3){
                echo $conn->error;
            }else{
                $row = $result3->fetch_assoc();
                $latestdeadliftid = $row['last_insert_id'];
            }
        }
    }

    //delete queries
    $deletecommand1 = "DELETE FROM PT_users_weeklyupdate WHERE id = '$latestbenchid'";
    $deletecommand2 = "DELETE FROM PT_users_weeklyupdate WHERE id = '$latestsquatid'";
    $deletecommand3 = "DELETE FROM PT_users_weeklyupdate WHERE id = '$latestdeadliftid'";

    $result1 = $conn->query($deletecommand1);

        if(!$result1){
            echo $conn->error;
        }else{
            $result2 = $conn->query($deletecommand2);

            if(!$result2){
                echo $conn->error;
            }else{
                $result3 = $conn->query($deletecommand3);

                if(!$result3){
                    echo $conn->error;
                }
            }
        }



}

//if submit stats has been pressed
if(isset($_POST['submitstatsbtn'])){
    //checking the stats input boxes aren't empty
    if((!empty($_POST['squatinput'])) && (!empty($_POST['deadliftinput'])) && (!empty($_POST['benchpressinput']))){

        $benchresult = $conn->real_escape_string($_POST['benchpressinput']);
        $squatresult = $conn->real_escape_string($_POST['squatinput']);
        $deadliftresult = $conn->real_escape_string($_POST['deadliftinput']);

        //inserting the new stats into the db
        $insertnewbenchdata = "INSERT INTO PT_users_weeklyupdate (users_id, exercise_type, result) 
        VALUES ('$iddata', '1', '$benchresult')";

        $insertnewsquatdata = "INSERT INTO PT_users_weeklyupdate (users_id, exercise_type, result) 
        VALUES ('$iddata', '2', '$squatresult')";

        $insertnewdeadliftdata = "INSERT INTO PT_users_weeklyupdate (users_id, exercise_type, result) 
        VALUES ('$iddata', '3', '$deadliftresult')";

        $result1 = $conn->query($insertnewbenchdata);

        if(!$result1){
            echo $conn->error;
        }else{
            $result2 = $conn->query($insertnewsquatdata);

            if(!$result2){
                echo $conn->error;
            }else{
                $result3 = $conn->query($insertnewdeadliftdata);

                if(!$result3){
                    echo $conn->error;
                }
            }
        }
    }
}

    //getting the clients info from db
    $getinfo = "SELECT * FROM PT_users WHERE users_id='$iddata'";
    
    $result = $conn->query($getinfo);

    if(!$result){
        echo $conn->error;
    } else{
        
        while($row=$result->fetch_assoc()){
            $firstname = $row['first_name'];
            $lastname = $row['last_name'];
            $profilepic = $row['profile_pic'];
            $coachcheck = $row['coach_boolean'];
        }

        //checking the user id exists and making sure they are not a coach
        if((empty($firstname)) || ($coachcheck == 1)){
            header('location: coach.php');
            exit();
        }
    }

    //arrays to hold clients exercise info that can then be feed to the chart js below
    $arrayofsquatresults = array();
    $arrayofdeadliftresults = array();
    $arrayofbenchresults = array();

    //squat results
    $getsquatinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$iddata' AND exercise_type='2' ORDER BY id ASC";

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
    $getdeadliftinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$iddata' AND exercise_type='3' ORDER BY id ASC";

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
    $getbenchinfo = "SELECT * FROM PT_users_weeklyupdate WHERE users_id='$iddata' AND exercise_type='1' ORDER BY id ASC";

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
    <title>User stats</title>
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

        <?php echo"<form method='POST' action='userinputstats.php?userid=$iddata' enctype='multipart/form-data'>";?>
            <div id="firstrowtrainee" class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">

                    <h1 id="mainwords" class="font-weight-bold font-italic text-white">
                        <?php echo "$firstname $lastname"; ?>
                    </h1>
                    <p><?php echo "<img src='$profilepic' width='150' height='150' alt=''>"; ?></p>

                    <h4 id="mainwords" class="font-weight-bold font-italic text-white">Weekly Strength Progression:</h4>
                    <canvas id="myChart"></canvas>
                    
                </div>
                <div class="col-sm-1"></div>
            </div>
        


        <div class="row pt-5">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    
                    <h4 id="mainwords" class="font-weight-bold font-italic text-white">Input new weeks set of stats:</h4>

                    <label for="squatinput" class="text-white">Squat:</label>
                    <p class="font-weight-bold font-italic text-white"><?php echo "<input type='text' class='form-control' name='squatinput' id='squatinput' placeholder='Squat kgs'>" ;?></p>

                    <label for="deadliftinput" class="text-white">Deadlift:</label>
                    <p class="font-weight-bold font-italic text-white"><?php echo "<input type='text' class='form-control' name='deadliftinput' id='deadliftinput' placeholder='Deadlift kgs'>" ;?></p>

                    <label for="benchpressinput" class="text-white">Benchpress:</label>
                    <p class="font-weight-bold font-italic text-white"><?php echo "<input type='text' class='form-control' name='benchpressinput' id='benchpressinput' placeholder='Benchpress kgs'>" ;?></p>

                    <button type="submit" class="btn btn-outline-info mb-2" name="submitstatsbtn">Submit this weeks stats</button>
                    <button type="submit" class="btn btn-danger mb-2" name="deletestatsbtn">Delete last weeks stats</button>
                    
                </div>
                <div class="col-sm-1"></div>
        </div>
        </form>


</body>

</html>