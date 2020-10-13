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

    $date = date('Y-m-d');
    
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8' />
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <link href='packages/core/main.css' rel='stylesheet' />
    <link href='packages/daygrid/main.css' rel='stylesheet' />
    <link href='packages/timegrid/main.css' rel='stylesheet' />
    <script src='packages/core/main.js'></script>
    <script src='packages/interaction/main.js'></script>
    <script src='packages/daygrid/main.js'></script>
    <script src='packages/timegrid/main.js'></script>
    
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid', 'timeGrid'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
            <?php
                echo "defaultDate: '$date',";
            ?>
                
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,

            dateClick: function(info) {
                document.getElementById("dateofevent").value = info.dateStr;
                $("#CreateBookingModal").modal("show");
                
                $('#CreateBookingModal').on('shown.bs.modal', function(e) {    

                    $('#start-time').timepicker({
                        zindex: 1600,
                        timeFormat: 'HH:mm:ss',
                        interval: 60,
                        minTime: '06:00',
                        maxTime: '9:00pm',
                        startTime: '06:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        container: '#CreateBookingModal modal-body'
                    });

                    $('#end-time').timepicker({
                        zindex: 1600,
                        timeFormat: 'HH:mm:ss',
                        interval: 60,
                        minTime: '06:00',
                        maxTime: '9:00pm',
                        startTime: '06:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        container: '#CreateBookingModal modal-body'
                    });
                });


            },

            editable: true,

            eventClick: function(info) {
                //document.getElementById("dateofchangingevent").value = info.event.start;
                document.getElementById("idofevent").value = info.event.id;
                
                $("#ModifyBookingModal").modal("show");
                
                $('#ModifyBookingModal').on('shown.bs.modal', function(e) {    

                    $('#change-start-time').timepicker({
                        zindex: 1600,
                        timeFormat: 'HH:mm:ss',
                        interval: 60,
                        minTime: '06:00',
                        maxTime: '9:00pm',
                        startTime: '06:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        container: '#CreateBookingModal modal-body'
                    });

                    $('#change-end-time').timepicker({
                        zindex: 1600,
                        timeFormat: 'HH:mm:ss',
                        interval: 60,
                        minTime: '06:00',
                        maxTime: '9:00pm',
                        startTime: '06:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        container: '#CreateBookingModal modal-body'
                    });
                });
            },

            eventLimit: true, // allow "more" link when too many events
            
            events: {
                url : 'bookings.php'
            },           
        
      });
        calendar.render();
    });

    </script>


    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>

    <title>Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <link href="../styles/mycss.css" rel="stylesheet">
</head>

<body>

    <div id="backgroundimages3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
            <a class="navbar-brand" href="../coach/coach.php">
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
                        <a class="nav-link" href="../coach/coachregisteruser.php">Register User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../coach/viewclients.php">Clients</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../coach/editwebsite.php">Edit Website</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../coach/viewmessages.php">View Messages</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../coach/uploadprograms.php">Upload Fitness Programs</a>
                    </li>
                </ul>

                <form method='POST' action='../logout.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Log Out</button>
                </form>

            </div>
        </nav>

        <div id='calendar' class="text-info"></div>

    </div>

    <div class="modal fade" id="CreateBookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="insertevent.php">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Booking for:</label>
                            <select class="form-control" name="bookingforinput">
                                <?php
                                    $selectusers = "SELECT * FROM PT_users WHERE coach_boolean='0'";
                                    $selectgroups = "SELECT DISTINCT group_number FROM PT_group ORDER BY group_number ASC";
                                    
                                    $userresult = $conn->query($selectusers);

                                    while($row=$userresult->fetch_assoc()){
                                    $firstname = $row['first_name'];
                                    $lastname = $row['last_name'];
                                    $userid = $row['users_id'];
                                    echo "<option value=$userid>$firstname $lastname</option>";
                                    }

                                    $groupresult = $conn->query($selectgroups);

                                    while($row=$groupresult->fetch_assoc()){
                                        $groupnumber = $row['group_number'];
                                        echo "<option value=G$groupnumber>Group $groupnumber</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Start time:</label>
                            <input type="text" class="form-control starttime" name="start-time" id="start-time">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">End time:</label>
                            <input type="text" class="form-control" name="end-time" id="end-time">
                        </div>
                        <div class="form-group">
                            <label for="message-text" id="booking-title" class="col-form-label">Booking title:</label>
                            <select class="form-control" id="booking-title" name="booking-title">
                                <?php
                                    $query = "SELECT * FROM PT_program_uploads";
                                    $queryresult = $conn->query($query);
    
                                    while($row=$queryresult->fetch_assoc()){
                                        $nameofprogram = $row['program_name'];
                                        echo "<option>$nameofprogram</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="dateofevent" name="dateofevent" value="3487">
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create booking</button>
                    </div>
                    </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModifyBookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Modify booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="editevent.php">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Change start time:</label>
                            <input type="text" class="form-control" name="change-start-time" id="change-start-time">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Change end time:</label>
                            <input type="text" class="form-control" name="change-end-time" id="change-end-time">
                        </div>
                        <div class="form-group">
                            <label for="message-text" id="booking-title" class="col-form-label">Change booking title:</label>
                            <select class="form-control" id="change-booking-title" name="change-booking-title">
                                <?php
                                    $query = "SELECT * FROM PT_program_uploads";
                                    $queryresult = $conn->query($query);
    
                                    while($row=$queryresult->fetch_assoc()){
                                        $nameofprogram = $row['program_name'];
                                        echo "<option>$nameofprogram</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="idofevent" name="idofevent" value="0">
                    
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="btnModify" class="btn btn-primary">Modify booking</button>
                        <button type="submit" name="btnDelete" class="btn btn-danger">Delete booking</button>
                    </div>
                    </form>
            </div>
        </div>
    </div>


    
</body>

</html>