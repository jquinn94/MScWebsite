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

//getting all the groups in ascending order
$clientqueryresult = "SELECT * FROM PT_group 
                        LEFT OUTER JOIN PT_users ON
                        PT_group.users_id=PT_users.users_id
                        ORDER BY group_number ASC";

//if the view all button has been pressed
if(isset($_POST['viewallbtn'])){
    header("location: viewclients.php");
    exit();
}

//if message button is pressed
if(isset($_POST['messagebtn'])){
    //making sure one of the groups has been selected
    if(!empty($_POST['check_list'])){
        $arrayofids = array();

        $groupid = $conn->real_escape_string($_POST['check_list']);

        $getgroupinfo = "SELECT * FROM `PT_group` WHERE group_number = '$groupid'";

        $result = $conn->query($getgroupinfo);

        if(!$result){
            echo $conn->error;
        }else{

            while($row=$result->fetch_assoc()){
                $userid = $row['users_id'];
                array_push($arrayofids, $userid);
            }

            //setting session variable of an array of clients
            $_SESSION['array_of_clients'] = $arrayofids;
            //setting session variable of group number
            $_SESSION['group_message'] = $groupid;
            header("location: groupmessage.php");
            exit();
        }

    }
}

//if delete group button has been pressed
if(isset($_POST['deletebtn'])){
    //making sure one of the groups has been selected
    if(!empty($_POST['check_list'])){

        //below goes through and deletes the group, any bookings assigned to the group and also any messages

        $groupid = $conn->real_escape_string($_POST['check_list']);
        $arrayofmessages = array();
        
        $deletecommand = "DELETE FROM PT_group WHERE group_number = '$groupid'";

        $result = $conn->query($deletecommand);

        if(!$result){
            echo $conn->error;
        }else{

            $deletecommand2 = "DELETE FROM PT_group_bookings WHERE booking_for = '$groupid'";

            $result2 = $conn->query($deletecommand2);

            if(!$result2){
                echo $conn->error;
            }else{

                $selectrowstodelete = "SELECT * FROM PT_group_messages WHERE group_number = '$groupid'";

                $result3 = $conn->query($selectrowstodelete);

                if(!$result3){
                    echo $conn->error;
                }else{

                    while($row=$result3->fetch_assoc()){
                        array_push($arrayofmessages, $row['message_content']);
                    }

                    $loopsize = count($arrayofmessages);

                    $deletecommand3 = "DELETE FROM PT_group_messages WHERE group_number = '$groupid'";

                    $result4 = $conn->query($deletecommand3);

                    if(!$result4){
                        echo $error->query;
                    }else{

                        for($loop = 0; $loop < $loopsize; $loop++){
                            
                            $deletecommand4 = "DELETE FROM PT_message_content WHERE content_id = '$arrayofmessages[$loop]'";

                            $result5 = $conn->query($deletecommand4);

                            if(!$result5){
                                echo $conn->error;
                            }

                        }
                    
                    }

                }

            }

        }

    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>View groups</title>
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
                $('.ClientCheckBox').prop('checked', this.checked);
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

            <form method='POST' action='viewgroups.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="viewallbtn">View All</button>
                    <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="submit" name="messagebtn">Message Group</button>
                    <button class="btn btn-outline-danger my-2 my-sm-0 ml-2" type="submit" name="deletebtn">Delete Group</button>
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
                        
                        echo "<div id ='bigscreenclients'>";
                       
                        echo "<div class='row row-cols-4 row-cols-md-4 pb-2' id='bigscreenclients'>";
                        
                        $oldgroupnumber = 0;

                        while($row = $result->fetch_assoc()){

                            $groupnumber = $row['group_number'];

                            if($groupnumber != $oldgroupnumber){
                                $oldgroupnumber = $groupnumber;
                                echo "</div>";
                                echo "<h2 class='text-white'>Group number $groupnumber</h2>
                                <div class='form-check'>
                                    <input class='form-check-input ClientCheckBox ml-3' type='radio' name='check_list' value='$groupnumber' id='defaultCheck1'>
                                    <label class='form-check-label text-white ml-5' for='defaultCheck1'>
                                            Select Group
                                    </label>
                                </div>";
                                echo "<div class='row row-cols-4 row-cols-md-4 pb-2' id='bigscreenclients'>";
                            }



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
                                        </div>
                                    </div>
                                </div>";
                        }

                        echo "</div>";
                        echo "</div>";
                        
                    }
  
                }
            ?>


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
            
        echo "<div id ='smallscreenclients'>";
        
        echo "<div class='row row-cols-1 row-cols-md-4 pb-2' id='smallscreenclients'>";
        
        $oldgroupnumber = 0;

        while($row = $result->fetch_assoc()){

            $groupnumber = $row['group_number'];

            if($groupnumber != $oldgroupnumber){
                $oldgroupnumber = $groupnumber;
                echo "</div>";
                echo "<h2 class='text-white'>Group number $groupnumber</h2>
                <div class='form-check'>
                    <input class='form-check-input ClientCheckBox ml-3' type='radio' name='check_list' value='$groupnumber' id='defaultCheck1'>
                    <label class='form-check-label text-white ml-5' for='defaultCheck1'>
                            Select Group
                    </label>
                </div>";
                
                echo "<div class='row row-cols-1 row-cols-md-4 pb-2' id='smallscreenclients'>";
            }



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
                        </div>
                    </div>
                </div>";
        }

        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</form>";
    }

}
?>


        </div>

    </div>


</body>

</html>