<?php
    include("../connections/conn.php");          
    session_start();

    //checking if user has logged in, if not back to index page
    if(!isset($_SESSION['authlogin'])){
        header('location:../index.php');
        exit();
    }
    
    //getting all the info of the event that the user is messaging about
    $emailofclient = $_SESSION['authlogin'];
    $idofevent = $conn->real_escape_string($_POST["idofevent"]);
    $dateofevent = $conn->real_escape_string($_POST["dateofevent"]);
    $titleofevent = $conn->real_escape_string($_POST["titleofevent"]);
    $message = $conn->real_escape_string($_POST["message"]);
    $messageplusinfo = "Cancellation Request: <br> Booking title: ".$titleofevent."<br> Date of event: ".$dateofevent."<br><br>".$message;

    //getting the info of the user messaging
    $getuserid = "SELECT * FROM PT_users WHERE email = '$emailofclient'";

    $result = $conn->query($getuserid);

    if(!$result){
        echo $conn->error;
    }else{
        $row=$result->fetch_assoc();
        $idofuser = $row['users_id'];

            $message2=$conn->real_escape_string($messageplusinfo);
            date_default_timezone_set('Europe/Dublin');
            $date = date("l jS M Y");
            $time = date("H:i:s");
                
            $sqlinsertmessage = 
                "
                INSERT INTO PT_message_content (content, date, time)
                VALUE ('$message2', '$date', '$time');
                ";

            $result2 = $conn->query($sqlinsertmessage);

            //inserting message into the database
            if(!$result2){
                echo $conn->error;
            }else{
                
                $sqlgetlastrowinsert = "SELECT MAX(PT_message_content.content_id) AS 'last_insert_id' FROM PT_message_content";
                $lastrowresult = $conn->query($sqlgetlastrowinsert);
                    
                if(!$lastrowresult){
                    echo $conn->error;
                }else{
                    $row=$lastrowresult->fetch_assoc();
                    $lastrow = $row['last_insert_id'];

                    //if event is a single booking 
                    if (strpos($idofevent, 'S') !== false) {
                        $getallcoachesid = "SELECT * FROM PT_users WHERE coach_boolean = '1'";

                        $result3 = $conn->query($getallcoachesid);

                        if(!$result3){
                            echo $conn->error;
                        }else{

                            while($row=$result3->fetch_assoc()){
                                $coachid = $row['users_id'];
                                $sqlinsertmessage2 = "INSERT INTO PT_internal_messages (message_to, message_from, message_content)
                                VALUES ('$coachid', '$idofuser', '$lastrow') ";
                
                                $result4 = $conn->query($sqlinsertmessage2);

                                if(!$result4){
                                    echo $conn->error;
                                }
                            }
                            header("location:clientcalendar.php");
                            exit();
                        }
                    //if event is a group booking 
                    }else{

                        $groupbookingid = str_replace("G", "", "$idofevent");

                        $getgroupnumber = "SELECT * FROM PT_group_bookings WHERE booking_id='$groupbookingid'";

                        $result5 = $conn->query($getgroupnumber);

                        if(!$result5){
                            echo $conn->error;
                            echo "2";
                        }else{
                            $row = $result5->fetch_assoc();
                            $groupnumber = $row['booking_for'];

                            $sqlinsertmessage2 = 
                            "INSERT INTO PT_group_messages (group_number, group_message_from, message_content)
                            VALUES ('$groupnumber', '$idofuser', '$lastrow');
                            ";

                            
                            $result6 = $conn->query($sqlinsertmessage2);

                            if(!$result6){
                                echo $conn->error;
                                echo "3";
                            }else{
                                header("location: clientcalendar.php");
                                exit();
                            }


                        }
                    }
                }
            } 
    }  
?>