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
    header('Location: coach.php');
    exit();
}

    //getting the info of the selected client
    $errormsgtouser = "";
    $getinfo = "SELECT * FROM PT_users WHERE users_id='$iddata'";
    
    $result = $conn->query($getinfo);

    if(!$result){
        echo $conn->error;
    } else{
        
            $row=$result->fetch_assoc();
            $firstname = $row['first_name'];
            $lastname = $row['last_name'];
            $dob_old = $row['dob'];
            $date = str_replace('/', '-', $dob_old);
            $dob = date("d-m-Y", strtotime($date));
            
            //this is so the dropdown menu is pre set to the value of the user
            $gender = $row['gender'];
            $selected_a = ($gender == 'Male') ? "selected" : "" ;
            
            $email = $row['email'];
            $phonenumber = $row['phone_number'];
            $profilepic = $row['profile_pic'];
            $users_id = $row['users_id'];
            $coachcheck = $row['coach_boolean'];

            //password is encryted in db so needs to be decrypted before showing to user
            $passinitial = "SELECT AES_DECRYPT(pass,'mykey') as pass FROM PT_users WHERE users_id='$iddata'";
            $result = $conn->query($passinitial);
            
            if(!$result){
                echo $conn->error;
            }
            
            $row=$result->fetch_assoc();
            $pass = $row['pass'];

        //if user id doesn't exist or if it is a coachs user id 
        if((empty($users_id)) || ($coachcheck == 1)){
            header("location: coach.php");
            exit();
        }

        $getaddressinfo = "SELECT * FROM PT_address WHERE users_id='$users_id'";

        $result2 = $conn->query($getaddressinfo);

        if(!$result2){
            echo $conn->error;
        } else{
            while($row=$result2->fetch_assoc()){
                $address1 = $row['address_first_line'];
                $address2 = $row['address_second_line'];
                $postcode = $row['address_postcode'];
                $country = $row['address_country'];
            }


        }

    }


//if delete user button has been pressed
if(isset($_POST['deleteuserbtn'])){


    //delete user from any group that they are associated with
    $deletecommand = "DELETE FROM PT_group WHERE users_id = '$users_id'";

    $result = $conn->query($deletecommand);

    if(!$result){
        echo $conn->error;
    }


    //delete group messages associated with the user
    $arrayofmessages = array();

    $selectrowstodelete = "SELECT * FROM PT_group_messages WHERE group_message_from = '$users_id'";

        $result3 = $conn->query($selectrowstodelete);

        if(!$result3){
            echo $conn->error;
        }else{

            while($row=$result3->fetch_assoc()){
                array_push($arrayofmessages, $row['message_content']);
            }

            $loopsize = count($arrayofmessages);

            $deletecommand3 = "DELETE FROM PT_group_messages WHERE group_message_from = '$users_id'";

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


    
    //delete any single bookings
    $deletecommand = "DELETE FROM PT_single_bookings WHERE booking_for = '$users_id'";
    
    $result = $conn->query($deletecommand);

    if(!$result){
        echo $conn->error;
    }




    //delete non-group messages associated with the user
    $arrayofmessages = array();

    $selectrowstodelete = "SELECT * FROM PT_internal_messages WHERE message_to = '$users_id' OR message_from = '$users_id'";

        $result3 = $conn->query($selectrowstodelete);

        if(!$result3){
            echo $conn->error;
        }else{

            while($row=$result3->fetch_assoc()){
                array_push($arrayofmessages, $row['message_content']);
            }

            $loopsize = count($arrayofmessages);

            $deletecommand3 = "DELETE FROM PT_internal_messages WHERE message_to = '$users_id' OR message_from = '$users_id'";

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



    //delete the users info from the PT_weekly_update table
    $deleteweeklyupdatecommand = "DELETE FROM PT_users_weeklyupdate WHERE users_id = '$users_id'";

    $result = $conn->query($deleteweeklyupdatecommand);

    if(!$result){
        echo $conn->error;
    }


    //delete the users info from the two details db tables
    $deletecommand5 = "DELETE FROM PT_address WHERE users_id = $users_id";
    $deletecommand6 = "DELETE FROM PT_users WHERE users_id = $users_id";

    $result1 = $conn->query($deletecommand5);

    if(!$result1){
        echo $conn->error;
    }else{
        $result2 = $conn->query($deletecommand6);

        if(!$result2){
            echo $conn->error;
        }else{
            header("location:viewclients.php");
        }
    }

}

//if submit changes button has been pressed
if(isset($_POST['submitchangesbtn'])){

    //checking if new profile picture has been selected to upload
    if(file_exists($_FILES['myfileupload']['tmp_name']) || is_uploaded_file($_FILES['myfileupload']['tmp_name'])) {
        $filedata = $_FILES['myfileupload']['tmp_name'];
        $filename = $_FILES['myfileupload']['name'];
       
        move_uploaded_file($filedata, "../imgs/$filename");
        $profilepicchange = "../imgs/".$filename;
    }else{
        $profilepicchange = $profilepic;
    }
    
    $firstnamechange = $conn->real_escape_string($_POST["firstnameinput"]);
    $lastnamechange = $conn->real_escape_string($_POST["lastnameinput"]);
    $dob_old1 = $conn->real_escape_string($_POST["dobinput"]);
    
    //changing format of dob
    if(!empty($dob_old1)){
        $date = str_replace('/', '-', $dob_old1);
        $dobchange = date("Y-m-d", strtotime($date));
    }else{
        $dobchange = $dob_old;
    }
    $genderchange = $conn->real_escape_string($_POST["genderinput"]);
    $emailchange = $conn->real_escape_string($_POST["emailinput"]);
    $phonechange = $conn->real_escape_string($_POST["phoneinput"]);
    $passwordchange = $conn->real_escape_string($_POST["passwordinput"]);
    $address1change = $conn->real_escape_string($_POST["address1input"]);
    $address2change = $conn->real_escape_string($_POST["address2input"]);
    $postcodechange = $conn->real_escape_string($_POST["postcodeinput"]);
    $countrychange = $conn->real_escape_string($_POST["countryinput"]);
    $roletype = $conn->real_escape_string($_POST["roletype"]);
    
    //checking the password contains one lowercase letter, one uppercase letter and one number
    if (preg_match('/[A-Z]/', $passwordchange) && preg_match('/[0-9]/', $passwordchange) && preg_match('/[a-z]/', $passwordchange))
    {
        //checking the phone number is numeric
        if(is_numeric($phonechange)){

            if($roletype == "Trainee"){
                $roletypefordb = 0;
            }else if($roletype == "Coach"){
                $roletypefordb = 1;
            }
            
            $changequery = 
                        "
            
                        UPDATE PT_users SET first_name='$firstnamechange', last_name='$lastnamechange', email='$emailchange', phone_number='$phonechange', pass=(AES_ENCRYPT('$passwordchange','mykey')), dob='$dobchange', gender='$genderchange', profile_pic='$profilepicchange', coach_boolean='$roletypefordb'
                        WHERE users_id='$users_id';
                        
                        UPDATE PT_address SET address_first_line='$address1change' , address_second_line = '$address2change', address_postcode = '$postcodechange', address_country = '$countrychange'
                        WHERE users_id='$users_id';
            
                        ";
            
            $result3 = $conn->multi_query($changequery);
            
            if(!$result3){
                $errormsg = $conn->error;

                if($errormsg == "Duplicate entry '$emailchange' for key 'email'"){
                $errormsgtouser = "*email has already been used for another user <br>";
                }
            }else{
                header("Location:viewuser.php?userid=$iddata");
                exit();
            }
        }else{
            $errormsgtouser = "*Phone number must be a number";
        }
    }else{
        $errormsgtouser = "Password must contain an uppercase letter, lowercase letter and a number";
    }

}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit user details</title>
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

        <div id="firstrowtrainee" class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <?php echo"<form method='POST' action='useredit.php?userid=$iddata' enctype='multipart/form-data'>";?>
                    <p><button type="submit" class="btn btn-outline-danger mb-2" name="deleteuserbtn">Delete User</button></p>
                    <p class="font-weight-bold font-italic text-danger"><?php echo $errormsgtouser ?></p>
                    <div class="row">
                        <div class="col">
                            <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='firstnameinput' value="<?php echo $firstname; ?>"></p>
                        </div>
                        <div class="col">
                            <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='lastnameinput' value="<?php echo $lastname; ?>"></p>
                        </div>
                    </div>
                    <p><?php echo "<img src='$profilepic' width='150' height='150' alt=''>"; ?></p>
                    <p class="font-weight-bold text-white">Upload new profile picture: <input name='myfileupload' type='file' class='inputfile'></p>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="text-white">Role: </label>
                        <select class="form-control" name="roletype">
                            <option>Trainee</option>
                            <option>Coach</option>
                        </select>
                    </div>

                    <label for="dobinput" class="text-white">Date of birth:</label>
                    <p class="font-weight-bold font-italic text-white"><?php echo "<input type='text' class='form-control' name='dobinput' id='datepicker' placeholder='$dob'>" ;?></p>
                    
                    <label for="genderinput" class="text-white">Gender:</label>
                    <p class="font-weight-bold font-italic text-white">
                    <?php echo "<select class='form-control' name='genderinput'>";
                            if(!empty($selected_a)){
                                echo "<option selected='selected' value='Male'>Male</option>
                                        <option value='Female'>Female</option>
                                        </select></p>";
                            }else{
                                echo "<option value='Male'>Male</option>
                                        <option selected='selected' value='Female'>Female</option>
                                        </select></p>";
                            }
                    ?>
                    
                    <label for="emailinput" class="text-white">Email:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='emailinput' value="<?php echo $email; ?>"></p>
                    
                    <label for="phoneinput" class="text-white">Phone number:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='phoneinput' value="<?php echo $phonenumber; ?>"></p>
                    
                    <label for="passwordinput" class="text-white">Password:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='passwordinput' value="<?php echo $pass; ?>"></p>
                    
                    <label for="address1input" class="text-white">Address line one:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='address1input' value="<?php echo $address1; ?>"></p>
                    
                    <label for="address2input" class="text-white">Address line two:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='address2input' value="<?php echo $address2; ?>"></p>
                    
                    <label for="postcodeinput" class="text-white">Postcode:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='postcodeinput' value="<?php echo $postcode; ?>"></p>
                    
                    <label for="countryinput" class="text-white">Country:</label>
                    <p class="font-weight-bold font-italic text-white"><input type='text' class='form-control' name='countryinput' value="<?php echo $country; ?>"></p>
                    <button type="submit" class="btn btn-outline-info mb-2" name="submitchangesbtn">Submit Changes</button>
                </form>
            </div>
        </div>

    </div>


</body>

</html>