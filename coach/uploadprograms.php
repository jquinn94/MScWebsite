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

//if the upload file button has been pressed 
if(isset($_POST['uploadfilebtn'])){

    //checking a file has been chosen
    if(file_exists($_FILES['myfileupload']['tmp_name']) || is_uploaded_file($_FILES['myfileupload']['tmp_name'])) {
        
        //getting the name and details of the file 
        $nameoffile = $conn->real_escape_string($_POST['nameinput']);
        $filedata = $_FILES['myfileupload']['tmp_name'];
        $filename = $_FILES['myfileupload']['name'];
       
        //moving the file to the correct folder on the server
        move_uploaded_file($filedata, "../trainingprograms/$filename");
        $fileaddress = "../trainingprograms/".$filename;

        //inserting the info of the new file into the db
        $insertfiletodb = "INSERT INTO PT_program_uploads (program_name, program_address) VALUES ('$nameoffile', '$fileaddress');";

        $result = $conn->query($insertfiletodb);

        if(!$result){
            echo $conn->error;
        }

    }

}

//if delete button has been pressed
if(isset($_POST['deletebtn'])){
    //getting which program that is selected to be deleted
    $value = $_POST['delete_program'];

    //getting program address from db
    $getaddress = "SELECT * FROM PT_program_uploads WHERE program_id = '$value'";
    $result1 = $conn->query($getaddress);

    if(!$result1){
        echo $conn->error;
    }else{
        $row=$result1->fetch_assoc();
        $address = $row['program_address'];
    }

    //deleting from database
    $deletecommand = "DELETE FROM PT_program_uploads WHERE program_id = '$value'";

    $result = $conn->query($deletecommand);

    if(!$result){
        echo $conn->error;
    }else{
        //deleting file from server
        unlink($address);
    }

}
    


?>

<!DOCTYPE html>
<html>

<head>
    <title>Upload programs</title>
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

                
                </ul>

                <form method='POST' action='../logout.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Log Out</button>
                </form>

            </div>
        </nav>

        <div id="firstrowtrainee" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <p class="font-weight-bold text-white">Current training schedules available: </p>
                <?php
                    $coaches = "SELECT * FROM PT_program_uploads";
                    $coachresult = $conn->query($coaches);

                    while($row=$coachresult->fetch_assoc()){
                        $nameofprogram = $row['program_name'];
                        echo "<p class='text-white'>$nameofprogram</p>";
                    }
                ?>
            </div>
            <div class="col-sm-1"></div>
        </div>
        
        <form method='POST' action="uploadprograms.php" enctype="multipart/form-data">
            <div id="firstrowtrainee" class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                <div class="form-group">
                            <label for="message-text" id="booking-title" class="col-form-label font-weight-bold text-white">Delete a training program (make sure no clients are assigned program):</label>
                            <select class="form-control" id="delete_program" name="delete_program">
                                <?php
                                    $query = "SELECT * FROM PT_program_uploads";
                                    $queryresult = $conn->query($query);
    
                                    while($row=$queryresult->fetch_assoc()){
                                        $nameofprogram = $row['program_name'];
                                        $programid = $row['program_id'];
                                        echo "<option value='$programid'>$nameofprogram</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    <button type="submit" name="deletebtn" class="btn btn-danger">Delete program</button>
                </div>

                <div class="col-sm-1"></div>
            </div>

            <div id="firstrowtrainee" class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <p class="font-weight-bold text-white">Upload new training schedule: </p>
                    <p><input type='text' class='form-control' name='nameinput' placeholder='Name of new training schedule'></p>
                    <p><input name='myfileupload'  type='file' class='inputfile font-weight-bold text-white' /></p>
                    <button type="submit" class="btn btn-outline-info mb-2" name="uploadfilebtn">Upload file</button>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </form>


        
       

</body>

</html>