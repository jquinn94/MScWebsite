<?php

session_start();

unset($_SESSION['authlogin']);
unset($_SESSION['traineecheck']);
unset($_SESSION['coachcheck']);

header('location:index.php');

?>