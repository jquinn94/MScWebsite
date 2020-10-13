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

//getting the homepage content from database
$homepagetitlequery = "SELECT * FROM PT_websitecontent WHERE id='1'";
$homepagedescriptionquery = "SELECT * FROM PT_websitecontent WHERE id='2'";

$result1 = $conn->query($homepagetitlequery);

if(!$result1){
        echo $conn->error;      
}else{
    $row=$result1->fetch_assoc();
    $homepagetitle = $row['content_description'];
}

$result2 = $conn->query($homepagedescriptionquery);

if(!$result2){
    echo $conn->error;
}else{
    $row=$result2->fetch_assoc();
    $homepagedescription = $row['content_description'];
}

//getting the aboutus content from database
$aboutustitlequery = "SELECT * FROM PT_websitecontent WHERE id='3'";
$aboutusdescriptionquery = "SELECT * FROM PT_websitecontent WHERE id='4'";

$result3 = $conn->query($aboutustitlequery);

if(!$result3){
    echo $conn->error;
}else{
    $row=$result3->fetch_assoc();
    $aboutustitle = $row['content_description'];
}

$result4 = $conn->query($aboutusdescriptionquery);

if(!$result4){
    echo $conn->error;
}else{
    $row=$result4->fetch_assoc();
    $aboutusdescription = $row['content_description'];
}

//if the submit changes button is pressed 
if(isset($_POST['submitchangesbtn'])){

    $homepagetitleupdate = $conn->real_escape_string($_POST['homepagetitle']);
    $homepagedescriptionupdate = $conn->real_escape_string($_POST['homepagedescrip']);
    $aboutustitleupdate = $conn->real_escape_string($_POST['aboutustitle']);
    $aboutusdescriptionupdate = $conn->real_escape_string($_POST['aboutusdescrip']);

    //queries to update the database 
    $sqlupdatequery = " UPDATE PT_websitecontent SET content_description='$homepagetitleupdate' WHERE id='1';
                        UPDATE PT_websitecontent SET content_description='$homepagedescriptionupdate' WHERE id='2';
                        UPDATE PT_websitecontent SET content_description='$aboutustitleupdate' WHERE id='3';
                        UPDATE PT_websitecontent SET content_description='$aboutusdescriptionupdate' WHERE id='4';";

    $updatewebsite = $conn->multi_query($sqlupdatequery);

    if(!$updatewebsite){
        echo $conn->error;
    }else{
        header("location:editwebsite.php");
        exit();
    }

}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Website</title>
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

        <div id="firstrowtrainee" class="row pb-5">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <form method='POST' action='editwebsite.php'>
                    <h2 class="font-weight-bold font-italic text-white">Home page edit</h2>
                    
                    <label for="homepagetitle" class="text-white">Home page title</label>
                    <p class="font-weight-bold"><input type='text' class='form-control' name='homepagetitle' placeholder="<?php echo $homepagetitle; ?>" value="<?php echo $homepagetitle; ?>"></p>
                    
                    <label for="homepagedescrip" class="text-white">Home page tag line</label>
                    <p class="font-weight-bold"><input type='text' class='form-control' name='homepagedescrip' placeholder="<?php echo $homepagedescription; ?>" value="<?php echo $homepagedescription; ?>"></p>

                    
                    
                    

                    <h2 class="font-weight-bold font-italic text-white mt-4">About Us page edit</h2>
                    
                    <label for="aboutustitle" class="text-white">About us page title</label>
                    <p class="font-weight-bold"><input type='text' class='form-control' name='aboutustitle' placeholder="<?php echo $aboutustitle; ?>" value="<?php echo $aboutustitle; ?>"></p>
                    
                    <label for="aboutusdescrip" class="text-white">About us page description</label>
                    <textarea class="form-control font-weight-bold" name="aboutusdescrip" rows="8"><?php echo $aboutusdescription; ?></textarea>

                    <p><input class="btn btn-outline-info mt-2" name='submitchangesbtn' type="submit" value="Submit Changes">
                    </p>
                </form>
            </div>
            <div class="col-sm-1"></div>
        </div>

    </div>


</body>

</html>