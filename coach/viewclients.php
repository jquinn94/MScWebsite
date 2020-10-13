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

$errormessage = "";

//checking if an error msg has been sent back from another page
if(isset($_GET['errormsg'])){
    $errormessage = $conn->real_escape_string($_GET['errormsg']);
}

//getting all clients, making sure they are not coaches
$clientqueryresult = "SELECT * FROM PT_users WHERE coach_boolean='0'";

//if the search button is pressed and the search box isn't empty
if((isset($_POST['searchbtn'])) && (!empty($_POST['searchinput']))){
    $lastnamesearch = $conn->real_escape_string($_POST['searchinput']);
    $clientqueryresult = "SELECT * FROM PT_users WHERE coach_boolean='0' AND last_name = '$lastnamesearch'";   
}

//checking if the view all button has been pressed
if(isset($_POST['viewallbtn'])){
    $clientqueryresult = "SELECT * FROM PT_users WHERE coach_boolean='0'";
}

//checking if the view groups button is pressed
if(isset($_POST['viewgroupsbtn'])){
    header("location: viewgroups.php");
    exit();
}

//checking if the message marked clients button was pressed
if(isset($_POST['messageclientsbtn'])){
    
    //checking to make sure at least one check box has been selected
    if(!empty($_POST['check_list'])){
        
        $arrayofids = array();

        // Loop to store and display values of individual checked checkbox.
        foreach($_POST['check_list'] as $selected){
            array_push($arrayofids, $selected);
        }

        //setting session variable of an array of clients
        $_SESSION['array_of_clients'] = $arrayofids;
        header("location: groupmessage.php");
        exit();

    }
}

    //checking if the make groups button was pressed
    if(isset($_POST['makegroupsbtn'])){

        //checking to make sure at least one check box has been selected
        if(!empty($_POST['check_list'])){
            
            $arrayofids = array();
    
            // Loop to store and display values of individual checked checkbox.
            foreach($_POST['check_list'] as $selected){
                array_push($arrayofids, $selected);
            }
    
            //setting session variable of an array of clients
            $_SESSION['array_of_clients'] = $arrayofids;
            header("location: makegroup.php");
            exit();
    
        }

    
    }


?>

<!DOCTYPE html>
<html>

<head>
    <title>View clients</title>
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
    <script>
        $(function() {
            $('#checkallcheckbox').click(function() {
                $('.ClientCheckBox:visible').prop('checked', this.checked);
            });
        });
    </script>
</head>

<body>

    <div id="backgroundimages3">
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

        <div class="row pt-3 ml-3">

            
            <form method='POST' action='viewclients.php' class="form-inline">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search by surname" name="searchinput">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="searchbtn">Search</button>
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="viewallbtn">View All</button>
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="viewgroupsbtn">View Groups</button>
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="messageclientsbtn">Message Checked Clients</button>
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="makegroupsbtn">Make Group From Checked Clients</button>
                    <?php echo "<p class='text-danger'>$errormessage <br></p>"; ?>
        </div>

        

            <?php

                $result = $conn->query($clientqueryresult);

                if(!$result){
                    echo $conn->error;
                } else{

                    $rowcount=mysqli_num_rows($result);

                    if($rowcount == 0){
                        echo "
                        <div class='row mt-5'>
                            <div class = 'col-2'></div>
                            <div class = 'col-8'>
                                <h3 class='font-weight-bold text-white'>No results found</h3>
                            </div>
                            <div class = 'col-2'></div>
                        </div>";
                    }else{

                        echo "
                        <div class='row'>
                            <div class = 'col ml-3'>
                                <div class='form-check'>
                                    <input class='form-check-input' type='checkbox' value='' id='checkallcheckbox'>
                                    <label class='form-check-label text-white' for='checkallcheckbox'>
                                        Select All
                                    </label>
                                </div>
                            </div>

                        </div>";
                        
                        echo "<div class='row row-cols-4 row-cols-md-4 pt-4' id='bigscreenclients'>";

                        while($row = $result->fetch_assoc()){

                            $firstname = $row['first_name'];
                            $lastname = $row['last_name'];
                            $profilepic = $row['profile_pic'];
                            $iddata = $row['users_id'];
        
                            echo "
                                <div class='col mb-4'>
                                    <div class='card text-white bg-dark'>
                                        <img src='$profilepic' width='150' height='200' class='card-img-top' alt=''>
                                        <div class='card-body'>
                                            <h5 class='card-title'>$firstname $lastname</h5>
                                            <p class='card-text'><a href='viewuser.php?userid=$iddata' class='btn btn-outline-info' role='button'>View profile</a></p>
                                            <div class='form-check'>
                                                <input class='form-check-input ClientCheckBox' type='checkbox' value='$iddata' name='check_list[]' id='ClientCheckBox'>
                                                <label class='form-check-label' for='ClientCheckBox'>
                                                    Select Client
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                        }

                        echo "</div>";
                        //echo "</form>";
                    }
  
                }
            ?>


            <?php

                $result = $conn->query($clientqueryresult);
                echo "<div class='row row-cols-1 row-cols-md-4 pt-4' id='smallscreenclients'>";

                if(!$result){
                    echo $conn->error;
                } else{
                    $rowcount=mysqli_num_rows($result);

                    if($rowcount == 0){
                        echo "
                        <div class='row mt-5'>
                            <div class = 'col-2'></div>
                            <div class = 'col-8'>
                                <h3 class='font-weight-bold text-white'>No results found</h3>
                            </div>
                            <div class = 'col-2'></div>
                        </div>";
                    }else{
                        
                        while($row = $result->fetch_assoc()){

                            $firstname = $row['first_name'];
                            $lastname = $row['last_name'];
                            $profilepic = $row['profile_pic'];
                            $iddata = $row['users_id'];
        
                            echo "
                                <div class='col mb-4'>
                                    <div class='card text-white bg-dark'>
                                        <img src='$profilepic' width='150' height='300' class='card-img-top' alt=''>
                                        <div class='card-body'>
                                            <h5 class='card-title'>$firstname $lastname</h5>
                                            <p class='card-text'><a href='viewuser.php?userid=$iddata' class='btn btn-outline-info' role='button'>View profile</a></p>
                                            <div class='form-check'>
                                            <input class='form-check-input ClientCheckBox' type='checkbox' name='check_list[]' value='$iddata' id='defaultCheck1'>
                                            <label class='form-check-label' for='defaultCheck1'>
                                                Select Client
                                            </label>
                                        </div>    
                                        </div>
                                    </div>
                                </div>";
                        }

                        echo "</div>";
                        echo "</form>";
                    }
                }
            ?>

        </div>

    </div>


</body>

</html>