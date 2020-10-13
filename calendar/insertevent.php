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

      //checking all needed input weren't left empty
      if(!empty($_POST["bookingforinput"]) && !empty($_POST["start-time"]) && !empty($_POST["end-time"]) && !empty($_POST["booking-title"]) && !empty($_POST["dateofevent"])){

        $bookingfor = $conn->real_escape_string($_POST["bookingforinput"]);
        $starttime = $conn->real_escape_string($_POST["start-time"]);
        $endtime = $conn->real_escape_string($_POST["end-time"]);
        $title = $conn->real_escape_string($_POST["booking-title"]);
        $date = $conn->real_escape_string($_POST["dateofevent"]);

        //if group booking
        if (strpos($bookingfor, 'G') !== false) {
            
            $groupbookingisfor =  str_replace("G","",$bookingfor);
            
            $insertbooking = "INSERT INTO PT_group_bookings (booking_title, booking_date, booking_start_time, booking_end_time, booking_for) 
            VALUES ('$title', '$date', '$starttime', '$endtime', '$groupbookingisfor')";

            $result = $conn->query($insertbooking);

            if(!$result){
                echo $conn->error;
            }else{
                header("location:coachcalendar.php");
                exit();
            }

        //if single booking
        }else{

            //echo "$bookingfor <br> $starttime <br> $endtime <br> $title <br> $date";
            $insertbooking = "INSERT INTO PT_single_bookings (booking_title, booking_date, booking_start_time, booking_end_time, booking_for) 
            VALUES ('$title', '$date', '$starttime', '$endtime', '$bookingfor')";

            $result = $conn->query($insertbooking);

            if(!$result){
                echo $conn->query;
            }else{
                header("location:coachcalendar.php");
                exit();
            }
        }

    }

    header("location:coachcalendar.php");
    exit();

?>