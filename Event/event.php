<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$query = $link->prepare("SELECT groups.group_id, groups.group_name FROM groups join belongs_to ON groups.group_id = belongs_to.group_id
    WHERE belongs_to.authorized = true AND belongs_to.username = ?") or die(mysqli_error($link));
$query->bind_param('s', $_SESSION['username']);
$query->execute();
$query->store_result();
$rowsGroups = $query->num_rows;
$query->bind_result($gid, $gName);

$groupData = array(array());

$i = 0;
while ($query->fetch()) {
	$groupData[$i] = array($gid, $gName);
	$i++;
}

$query2 = $link->prepare("SELECT lname, zip FROM location") or die(mysqli_error($link));
$query2->execute();
$query2->store_result();
$rowsLocs = $query2->num_rows;
$query2->bind_result($lname, $zipCode);

$locData = array(array());

$i = 0;
while ($query2->fetch()) {
	$locData[$i] = array($lname, $zipCode);
	$i++;
}

$_SESSION['lastLoc'] = "event";
//create a form on this page that submits to createEvent.php
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create Event</title>
  		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../CSS Files/event.css">
        <script src = "../Javascript/event.js"></script>
	</head>
	
	<body>
	<!--startpage-->
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = banner> Create an Event </div>
			</div>
		</row>
		</div>
		<!--Navbar-->
		<nav class="navbar navbar-default">
			<div class = "col-md-3 text-center"> <a href = "../homepage.php" class="btn btn-danger" role="button"> Home </a> </div>
			<div class = "col-md-3 text-center"> <a href = "../Group/group.php" class="btn btn-primary" role="button"> Group </a> </div>
			<div class = "col-md-3 text-center"> <a href = "event.php" class="btn btn-info" role="button"> Create Event </a> </div>
			<div class = "col-md-3 text-center"> <a href = "../logout.php" class="btn btn-warning" role="button"> Logout </a> </div>
		</nav>
			<!--Insert:
			See the meetups that are occurring during some date range
			-->
			<?php
			?>
		</div>
		<div class = "hiddenGroups" hidden>
			<?php
				for ($i = 0; $i < $rowsGroups; $i++ ) {
				}
			?>
		</div>
		<!--Make the above two divs into two boxes left and right?
		With a dropdown for interest and have the groups displayed under it
		Infinite scroll vs click to load more-->

		<!--Create Event-->
		<div class = cEvent> <!-- style = "display:none;" -->
			<h3> Create Event </h3>

			<form name = "create_event" action = "createEvent.php" method = "POST">
				<label for = "title"> Title </label>
				<input class = title type = "text" id = "title" name = "title" required="required" value = ""/> <br />
				
				<label for = "description"> Description </label>
				<input class = desc type = "text" id = "description" name = "description" required="required" value = ""/> <br />
				
				<label for = "start_date"> Start Date </label>
				<input class = start type = "date" id = "start_date" name = "start_date" required="required" value = ""/> <br />
				
				<label for = "end_date"> End Date </label>
				<input class = end type = "date" id = "end_date" name = "end_date" required="required" value = "" /> <br />
				
				<label for = "start_time"> Start Time </label>
				<input class = start type = "time" id = "start_time" name = "start_time" required="required" value = ""/> <br />
				
				<label for = "end_time"> End Time </label>
				<input class = end type = "time" id = "end_time" name = "end_time" required="required" value = "" /> <br />
				
				<div class = "col-md-6">
			 		<div class="btn-group pull-right">
			  			<button id = "bttn1" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Group
			  				<span class="caret"></span>
			  			</button>
						<ul id = "groupData" class="dropdown-menu" role="menu" >
							<?php
			  				for ($i = 0; $i < $rowsGroups; $i++ ) {
			  					echo "<li><a href = '#'>".$groupData[$i][0]."-".$groupData[$i][1]."</a></li>";
			  				}
			  				?>
			  			</ul>
					</div>
				</div>
				<div class = "col-md-6">
			 		<div class="btn-group pull-left">
			  			<button id = "bttn2" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Location
			  				<span class="caret"></span>
			  			</button>
			  			<ul id = "locData" class="dropdown-menu" role="menu" >
							<?php
		  					for ($i = 0; $i < $rowsLocs; $i++ ) {
		  						echo "<li><a href = '#'>".$locData[$i][0]."-".$locData[$i][1]."</a></li>";
		  				}
		  				?>
			  			</ul>
					</div>
				</div>
					
					<div class = hiddenBoxes hidden>
						<label for = "groupId"> groupData</label>
						<input type = textbox class = inputbox name = groupId id = box1>  <br />
						
						<label for = "locationName"> locData</label>
						<input type = textbox class = inputbox name = locationName id = box2>  <br />
						
						<label for = "zip"> zipData</label>
						<input type = textbox class = inputbox name = zip id = box3>  <br />
					</div>
				
				<input type = "submit" class = submit>	
			</form>
		</div>
	</body>
</html>