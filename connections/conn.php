<?php

$user = "jquinn63";
$pw = "0M6km9DVcV69qBVd";
$server = "jquinn63.lampt.eeecs.qub.ac.uk";
$db = "jquinn63";

$conn = new mysqli($server, $user, $pw, $db);

if($conn->connect_error){
    echo $conn->connect_error;
}

?>