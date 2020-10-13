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
        header('location:../index.php');
        exit();
    }

    //getting all groups from the db
    $getgroupnumber = "SELECT * FROM PT_group";
    $result1 = $conn->query($getgroupnumber);

    if(!$result1){
        echo $conn->error;
    }else{
        
        //checking there is any groups
        $rowcount = mysqli_num_rows($result1);
        
        //if no groups then new group will be group 1
        if($rowcount == 0){
            $groupnumber = 1;
        }else{

            //getting all group numbers in order
            $getlastgroupmade = "SELECT DISTINCT(PT_group.group_number) FROM PT_group ORDER BY group_number";
            $result2 = $conn->query($getlastgroupmade);
            $count = 1;

            if(!$result2){
                echo $conn->error;
            }else{
                //cycling through the list of group numbers until a number is available
                while($row = $result2->fetch_assoc()){
                    if($row['group_number'] != $count){
                        break;
                    }else{
                        $count++;
                    }
                }
                $groupnumber = $count;
            }
        }

        //getting the session variable - which is an array of clients to add to the new group  
        $arrayofclients = $_SESSION['array_of_clients'];
        $arrayofclientids = array();
    
        //insert each client into the group
        foreach ($arrayofclients as $value){
    
            $insertclienttogroup = "INSERT INTO PT_group (users_id, group_number) VALUES ($value, $groupnumber)";
    
            $result3 = $conn->query($insertclienttogroup);

            if(!$result3){
                
                $errormsg = $conn->error;
                
                //checking the client to add to the new group doesn't already exist in another group
                if (strpos($errormsg, "Duplicate entry '$value' for key 'users_id'") !== false) {
                    $errormessage = "*One of the users is already in a group";
                    header("location: viewclients.php?errormsg=$errormessage");
                    exit();
                }else{
                    echo $conn->error;
                    exit();
                }
                
            }
        }

            //inserting a message into the database to tell every client that was added to the new group that they have been added
            date_default_timezone_set('Europe/Dublin');
            $date = date("l jS M Y");
            $time = date("H:i:s");

            $messagecontent = "INSERT INTO PT_message_content (content, date, time) VALUES ('Group $groupnumber has been created', '$date', '$time')";

            $result4 = $conn->query($messagecontent);

            if(!$result4){
                $conn->error;
            }else{

                $getlastmessageinserted = "SELECT * FROM PT_message_content ORDER BY content_id DESC LIMIT 1";

                $result5 = $conn->query($getlastmessageinserted);

                if(!$result5){
                    echo $conn->error;
                }else{

                    $row=$result5->fetch_assoc();
                    $messagecontentid = $row['content_id'];
                    $coachemail = $_SESSION['authlogin'];

                    $getmessagefrominfo = "SELECT * FROM PT_users WHERE email ='$coachemail'";

                    $result6 = $conn->query($getmessagefrominfo);

                    if(!$result6){
                        echo $conn->error;
                    }else{
                        $row = $result6->fetch_assoc();
                        $coachid = $row['users_id'];

                        $insertgroupmessage = "INSERT INTO PT_group_messages (group_number, group_message_from, message_content) VALUES ($groupnumber, $coachid, $messagecontentid)";

                        echo $insertgroupmessage;

                        $result7 = $conn->query($insertgroupmessage);

                        if(!$result7){
                            echo $conn->error;
                        }

                    }

                }

                    
            }

        header("location: viewclients.php");
        exit();

    }
    
?>
