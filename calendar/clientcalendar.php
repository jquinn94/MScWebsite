<?php
    include("../connections/conn.php");          
    session_start();

    //checking if user has logged in, if not back to index page
    if(!isset($_SESSION['authlogin'])){
      header('location:../index.php');
      exit();
    }
    
    //checking the user that has logged in is a trainee and not a coach
    if(!isset($_SESSION['traineecheck'])){
        header('location:../coach/coach.php');
        exit();
    }

    $date = date('Y-m-d');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8' />
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
        selectable: false,
        selectMirror: true,
        editable: true,

        eventClick: function(info) {
          document.getElementById("idofevent").value = info.event.id;
          document.getElementById("titleofevent").value = info.event.title;
          document.getElementById("dateofevent").value = info.event.start;

          $("#MessageCoach").modal("show");
        },

        eventLimit: true, // allow "more" link when too many events

        events: {
          url : 'clientbookings.php'
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

  <div id="backgroundimages3">
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
      <a class="navbar-brand" href="../trainee/trainee.php">
        <img src="../imgs/logo1.jpg" width="35" height="30" class="d-inline-block align-top" alt="">
        GymShark Fitness
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Personal Trainers
            </a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php
                $coaches = "SELECT * FROM PT_users WHERE coach_boolean='1'";
                $coachresult = $conn->query($coaches);

                while($row=$coachresult->fetch_assoc()){
                  $firstname = $row['first_name'];
                  $lastname = $row['last_name'];
                  $coachid = $row['users_id'];
                  echo "<a class='dropdown-item' href='../trainee/viewcoach.php?id=$coachid'>$firstname $lastname</a>";
                }
              ?>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../trainee/viewmessages.php">View Messages</a>
          </li>
          <li class="nav-item">
                        <a class="nav-link" href="../trainee/viewstats.php">View Stats</a>
          </li>

          <li class="nav-item">
                        <a class="nav-link" href="../trainee/viewprograms.php">View Programs</a>
          </li>
        </ul>

        <form method='POST' action='../logout.php' class="form-inline">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Log Out</button>
        </form>

      </div>
    </nav>

    <div id='calendar' class="text-info"></div>

    <div class="modal fade" id="MessageCoach" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="messagecoach.php">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Message to coach:</label>
                            <textarea class="form-control starttime" name="message" id="message" rows="5"></textarea>
                        </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Request</button>
                    </div>
                    <input type="hidden" id="idofevent" name="idofevent" value="0">
                    <input type="hidden" id="titleofevent" name="titleofevent" value="0">
                    <input type="hidden" id="dateofevent" name="dateofevent" value="0">
                    </form>
            </div>
        </div>
    </div>


  </div>
</body>

</html>