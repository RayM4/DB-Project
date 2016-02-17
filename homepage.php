<?php   
$link = mysqli_connect("localhost", "raym4", "", "meetup");

session_start();
//var_dump($_POST);
$lifetime = 86400;
session_set_cookie_params($lifetime, $httponly = true);
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

//&& $username != $_SESSION['username']
if (!(isset($_SESSION['username']))) {
	//echo "2";
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	$query = mysqli_prepare($link, "SELECT username, password FROM member WHERE username = ? ");
	mysqli_stmt_bind_param($query, 's', $username);
	mysqli_stmt_execute($query);
	mysqli_stmt_store_result($query);
	$rows = mysqli_stmt_num_rows($query);
	mysqli_stmt_bind_result($query, $user, $pass);
	mysqli_stmt_fetch($query);
	

	if ($rows == 1) {
		if ($password == $pass) {
			$_SESSION['username'] = $username;
		} else { //password is just wrong or something went wrong with the hashing
			echo "wrong hash or pass";
			
			header("location:index.php?invalidcredA");
			exit();
		}
	} else { //wrong username or multiple entries
		header("location:index.php?invalidcredB");
		exit();
	}
}
//incase someone trys to enter the site with a different log in while logged in
$username = $_SESSION['username'];
//$username = $_SESSION['username'];
//echo "logged in ";
//echo $username;
/*
if (isset($_SESSION['lastLoc'])) {
	if ($_SESSION['lastLoc'] == "event") {
		if ($_SESSION["eventCreated"]) {
		$_SESSION["eventCreated"] = false;
		//add something to show that it was created
		echo "<script type='text/javascript'>alert('Event was sucessfully created');</script>";
		} else {
			echo "<script type='text/javascript'>alert('Event was not created');</script>";
		}	
	}

	if ($_SESSION['lastLoc'] == "group") {
		if ($_SESSION["groupCreated"]) {
		$_SESSION["groupCreated"] = false;
		//add something to show that it was created
		echo "<script type='text/javascript'>alert('Group was sucessfully created');</script>";
		} else {
			echo "<script type='text/javascript'>alert('Group was not created');</script>";
		}
	}
}*/



$query1 = $link->prepare("SELECT groups.group_id, groups.group_name, groups.description, groups.username FROM groups join belongs_to ON groups.group_id = belongs_to.group_id
    WHERE belongs_to.username = ?") or die(mysqli_error($link));
$query1->bind_param('s', $_SESSION['username']);
$query1->execute();
$query1->store_result();
$rowsGroups = $query1->num_rows;
$query1->bind_result($gid, $gName, $gdesc, $gleader);

$groupData = array(array());

$i = 0;
while ($query1->fetch()) {
	$groupData[$i] = array($gid, $gName, $gdesc, $gleader);
	$i++;
}

$query2 = $link->prepare("SELECT * FROM events WHERE start_time BETWEEN ? AND ?") or die(mysqli_error($link));
$today = date("Y-m-d");
$week = date("Y-m-d", strtotime("+4 week"));
$query2->bind_param('ss', $today, $week);
$query2->execute();
$query2->store_result();
$rowsEvents = $query2->num_rows;
$query2->bind_result($eid, $title, $description, $start, $end, $gid, $loc, $zip);

$eventData = array(array());

$i = 0;
while ($query2->fetch()) {
	$eventData[$i] = array($eid, $title, $description, $start, $end, $gid, $loc, $zip);
	$i++;
}

$query3 = $link->prepare("SELECT * FROM events join attend ON events.event_id = attend.event_id
	WHERE rsvp = 1 AND attend.username = ?");
$query3->bind_param('s',$username);
$query3->execute();
$query3->store_result();
$rowsRSVP = $query3->num_rows;
$query3->bind_result($eid, $title, $description, $start, $end, $gid, $loc, $zip, $eid, $user, $rsvp, $rating);

$rsvpData = array(array());
$i = 0;
while ($query3->fetch()) {
	$rsvpData[$i] = array($eid, $title, $description, $start, $end, $gid, $loc, $zip, $eid, $user, $rsvp, $rating);
	$i++;
}

$query4 = $link->prepare("SELECT event_id FROM attend WHERE rsvp = 1 AND username = ?");
$query4->bind_param('s',$username);
$query4->execute();
$query4->store_result();
$rowsAttend = $query4->num_rows;
$query4->bind_result($attendID);
$attendData = array();
$i = 0;
while ($query4->fetch()) {
	$attendData[$i] = $attendID;
	$i++;
}

$_SESSION['lastLoc'] = "home";
?>

<?php ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Group</title>
  		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="CSS Files/homepage.css">
        <script src = "Javascript/homepage.js"></script>
    </head>
    <body> 
    <!--Group Creation page-->
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = banner> Home </div>
			</div>
		</row>
		</div>
        <!--Navbar-->
		<nav class="navbar navbar-default">
			<div class = "col-md-3 text-center"> <a href = "homepage.php" class="btn btn-danger" role="button"> Home </a> </div>
			<div class = "col-md-3 text-center"> <a href = "Group/group.php" class="btn btn-primary" role="button"> Group </a> </div>
			<div class = "col-md-3 text-center"> <a href = "Event/event.php" class="btn btn-info" role="button"> Create Event </a> </div>
			<div class = "col-md-3 text-center"> <a href = "logout.php" class="btn btn-warning" role="button"> Logout </a> </div>
		</nav>
		 <!--Welcome Message-->
		<div class = welcome> Oh no! It's <?php echo $_SESSION['username'] ?> again! D: </div>
		
		<span class = "col-md-4 text-center"> Upcoming Events (Events you RSVP to)
		
		</span>
		<span class = "col-md-4 text-center"> Pending Events (Events for the next month)
		
		</span>
		<span class = "col-md-4 text-center"> Groups you are part of 
		
		</span>
		
	  		<div class="row-fluid"> 
				<div class="col-lg-4">
					<div class = upcoming> 
						<?php
	  					for ($i = 0; $i < $rowsRSVP; $i++ ) {
	  						echo "<span>".$rsvpData[$i][1]." - ".$rsvpData[$i][2]."</span>";
	  						echo "<span>".$rsvpData[$i][3]." - ".$rsvpData[$i][4]."</span>";
	  						echo "<span>".$rsvpData[$i][6]."</span></br></br>";
	  						}
	  					?>
					</div>
				</div>
				<div class="col-lg-4">
					<div class = pending> 
						<?php
	  					for ($i = 0; $i < $rowsEvents; $i++ ) {
	  						if (!in_array($eventData[$i][0], $attendData)) {
	  							echo "<form action = 'rsvp.php' method = 'POST'> <input hidden name = event_id value = ".$eventData[$i][0].">"; //this is hidden
	  							echo "<span>".$eventData[$i][1]." - ".$eventData[$i][2]."<button class = rsvp type = submit> RSVP </button></span>";
	  							echo "<span>".$eventData[$i][3]." - ".$eventData[$i][4]."</span>";
	  							echo "<span>".$eventData[$i][6]."</span></form></br>";
	  						}
	  						
	  						}
	  					?>					
					</div>
				</div>
				<div class="col-lg-4">
					<div class = groups>
						<?php
	  					for ($i = 0; $i < $rowsGroups; $i++ ) {
	  						echo "<form action = 'Group/leaveGroup.php' method = 'POST'> <input hidden name = group_id value = ".$groupData[$i][0].">";
	  						echo "<span>".$groupData[$i][1]." - ".$groupData[$i][2]."<button class = leave type = submit> LEAVE </button></span>";
	  						echo "<span> Leader: ".$groupData[$i][3]."</span></form></br></br>";
	  						}
	  					?>	
					</div>
				</div>
			</div>
		
    </body>
</html>
