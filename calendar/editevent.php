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
      header('location:../trainee/trainee.php');
      exit();
    }

    //get all the edited information
    $starttime = $conn->real_escape_string($_POST["change-start-time"]);
    $endtime = $conn->real_escape_string($_POST["change-end-time"]);
    $title = $conn->real_escape_string($_POST["change-booking-title"]);
    $idofevent = $conn->real_escape_string($_POST["idofevent"]);


    //if delete button was pressed
    if (isset($_POST['btnDelete'])) {
        
        //delete if it was a group event selected
        if (strpos($idofevent, 'G') !== false) {
        
            $groupbookingisfor =  str_replace("G","",$idofevent);

            $deletecommand = "DELETE FROM PT_group_bookings WHERE booking_id='$groupbookingisfor'";

            $result = $conn->query($deletecommand);

            if(!$result){
                echo $conn->error;
            }else{
                header('location:coachcalendar.php');
                exit();
            } 
        
        //delete if it was a single event selected
        }else{
            
            $groupbookingisfor =  str_replace("S","",$idofevent);

            $deletecommand = "DELETE FROM PT_single_bookings WHERE booking_id='$groupbookingisfor'";

            $result = $conn->query($deletecommand);

            if(!$result){
                echo $conn->error;
            }else{
                header('location:coachcalendar.php');
                exit();
            } 
        
        }
        
    //if edit event button was pressed
    } else {

        //edit if it was a group event selected
        if (strpos($idofevent, 'G') !== false) {
        
            $groupbookingisfor =  str_replace("G","",$idofevent);

            $updatecommand = "UPDATE PT_group_bookings SET booking_title='$title', booking_start_time='$starttime', booking_end_time='$endtime'
                                WHERE booking_id='$groupbookingisfor'";

            $result = $conn->query($updatecommand);

            if(!$result){
                echo $conn->error;
            }else{
                header('location:coachcalendar.php');
                exit();
            } 
        
        //edit if it was a single event selected
        }else{
            
            $groupbookingisfor =  str_replace("S","",$idofevent);

            $updatecommand = "UPDATE PT_single_bookings SET booking_title='$title', booking_start_time='$starttime', booking_end_time='$endtime'
                                WHERE booking_id='$groupbookingisfor'";

            $result = $conn->query($updatecommand);

            if(!$result){
                echo $conn->error;
            }else{
                header('location:coachcalendar.php');
                exit();
            } 
        
        }
  
    }
      
?>