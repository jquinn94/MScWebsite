<?php
    include("../connections/conn.php");          
    session_start();

    //checking if user has logged in, if not back to index page
    if(!isset($_SESSION['authlogin'])){
        header('location:../index.php');
        exit();
    }
    
    $arrayofeventtitles = array();
    $arrayofeventstarttimes = array();
    $arrayofeventendtimes = array();
    $arrayofeventids = array();
    $email = $_SESSION['authlogin'];

    //getting all single bookings from the calendar
    $getcalendarevents = "SELECT * FROM PT_single_bookings
                            LEFT OUTER JOIN PT_users ON
                            PT_users.users_id = PT_single_bookings.booking_for
                            WHERE email='$email'";

    $result = $conn->query($getcalendarevents);

    if(!$result){
        echo $conn->error;
    }else{

        //putting all single bookings into arrays
        while($row = $result->fetch_assoc()){
            $bookingforfirstname = $row['first_name'];
            $bookingforlastname = $row['last_name'];
            $usersid = $row['users_id'];
            $date = $row['booking_date'];
            $starttime = $row['booking_start_time'];
            $endtime = $row['booking_end_time'];
            $title = $row['booking_title'];
            $id = "S".$row['booking_id'];

            $titletoshowoncalendar = $title;

            $starttimeoncalendar = $date."T".$starttime;
            $endtimeoncalendar = $date."T".$endtime;

            array_push($arrayofeventtitles, $titletoshowoncalendar);
            array_push($arrayofeventstarttimes, $starttimeoncalendar);
            array_push($arrayofeventendtimes, $endtimeoncalendar);
            array_push($arrayofeventids, $id);
        }

        //getting all group bookings
        $getgroupnumber = "SELECT * FROM PT_group
                            LEFT OUTER JOIN PT_users ON
                            PT_users.users_id=PT_group.users_id
                            WHERE email='$email'";

        $result2 = $conn->query($getgroupnumber);

        if(!$result2){
            echo $conn->error;
        }else{

            $row = $result2->fetch_assoc();

            $group_number = $row['group_number'];

            $getcalendarevents2 = "SELECT * FROM PT_group_bookings WHERE booking_for = '$group_number'";

            $result3 = $conn->query($getcalendarevents2);
    
            if(!$result3){
                echo $conn->error;
            }else{
                //putting all group bookings into arrays
                while($row = $result3->fetch_assoc()){
                    $date = $row['booking_date'];
                    $starttime = $row['booking_start_time'];
                    $endtime = $row['booking_end_time'];
                    $title = $row['booking_title'];
                    $groupnumber = $row['booking_for'];
                    $id = "G".$row['booking_id'];
        
                    $titletoshowoncalendar = $title." for Group".$groupnumber;
        
                    $starttimeoncalendar = $date."T".$starttime;
                    $endtimeoncalendar = $date."T".$endtime;
        
                    array_push($arrayofeventtitles, $titletoshowoncalendar);
                    array_push($arrayofeventstarttimes, $starttimeoncalendar);
                    array_push($arrayofeventendtimes, $endtimeoncalendar);
                    array_push($arrayofeventids, $id);
                }

            }

        }

        $loopsizeouter = count($arrayofeventtitles);

    }

    $events = array();

    //putting all events into an arrays
    for($loop = 0; $loop < $loopsizeouter; $loop++){
        
        $agenda['allDay'] = false;
        $agenda['start'] = $arrayofeventstarttimes[$loop];
        $agenda['end'] = $arrayofeventendtimes[$loop];
        $agenda['title'] = $arrayofeventtitles[$loop];
        $agenda['id']= $arrayofeventids[$loop];
        $events[] = $agenda;
    }

//encoding it to json so calendar can understand it
echo json_encode($events);
exit();
?>