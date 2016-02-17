<?php
$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$query = $link->prepare("SELECT * FROM groups") or die(mysqli_error($link));
$query->execute();
$query->store_result();
$rowsGroups = $query->num_rows;
$query->bind_result($gid, $gName, $gdesc, $leader);

$groupData = array(array());

$i = 0;
while ($query->fetch()) {
	$groupData[$i] = array($gid, $gName, $gdesc, $leader);
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


$query3 = $link->prepare("SELECT * FROM interest") or die(mysqli_error($link));
$query3->execute();
$query3->store_result();
$rowsInterest = $query3->num_rows;
$query3->bind_result($interest);

$interestData = array();

$i = 0;
while ($query3->fetch()) {
	$interestData[$i] = $interest;
	$i++;
}

/*
//find group's interest based on their name
//returns an array
function findInterests($groupName) {
	global $aboutData;
	global $rowsAbout;
	global $groupData;
	global $rowsGroups;
	$data = array();
	$group_ID;
	for ($i = 0; $i < $rowsGroups; $i++) {
		if ($groupData[$i][1] == $groupName) {
			$group_ID = $groupData[$i][0];
		}
	}
	
	$count = 0;
	for ($i = 0; $i < $rowsAbout; $i++) {
		if ($aboutData[$i][1] == $group_ID) { //aboutData[i][1] is an id not a name
			$data[$count] = $aboutData[$i][0];
			$count++;
		} 
	}
	return $data;
}*/

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Meetup</title>
  		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="CSS Files/index.css">
        <script src = "Javascript/javascript.js"></script>
	</head>
	
	<body>
	<!--startpage-->
	<div class = "startpage">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = banner> Meetup </div>
			</div>
		</row>
			<!--Insert:
			See the meetups that are occurring during some date range
			-->
			<?php
			?>
		</div>
		
		<div id = "interest_list"> 
			<h2> Interest </h2>
			<!--Insert:
			select an interest and see list of groups that share that interest
			-->
			<!-- use $interestData to populate the drop down menu -->
		</div>
		
		<form name = "interest_form" action = "Group/anonGroup.php" method = "POST">	
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="input-group">   	
					<input name = interest type="TextBox" ID="inputbox" Class="form-control" value =""  required="required"></input>	
			 		<div class="input-group-btn">
			  			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Interest
			  				<span class="caret"></span>
			  			</button>
			  			<ul id = interestList class="dropdown-menu">
			  				<?php
			  				for ($i = 0; $i < $rowsInterest; $i++ ) {
			  					//echo "<li><a href = '".$interestData[$i]."'>".$interestData[$i]."</a></li>";
			  					echo "<li><a href = '#'>".$interestData[$i]."</a></li>";
			  					//echo "<li>".$interestData[$i]."</li>";
			  					//findGroupIDs(data from drop down) <---the return value is an array of group names
			  				}
			  				?>
			  			</ul>
					</div>
				</div>
			</div>
		</div>
			
			<!--input type = textbox class = inputbox name = interest id = interestBox-->	
			<input type = "submit">
		</form>
		</div>
	<!--Make the above two divs into two boxes left and right?
	With a dropdown for interest and have the groups displayed under it
	Infinite scroll vs click to load more-->
	
	<!-- TO DO: onclicks for the text fields-->
	<!--login-->
	<div id = "login_form"> <!-- style = "display:none;" -->
		<h3>Log in </h3>
		
		<div>
			<form name="login" action="homepage.php" method="POST">
				<label for="username">Username</label>
				<input type = "text" id = "username" name = "username" required="required" value = ""/> <br />
				<label for="password">Password</label>
				<input class = pw type = "password" id = "password" name = "password" required="required" value = ""/> <br />
				<input type = "submit">
			</form>
		</div>
	</div>
	
	<!--register-->
	<div id = "reg_form"> <!-- style = "display:none;" -->
		<h3> Register </h3>
		
		<div>
			<form name = "register" action = "register.php" method = "POST">
				<label for = "firstname"> First Name </label>
				<input class = "fname" type = "text" id = "firstname" name = "firstname" required="required" value = ""/> <br />
				
				<label for = "lastname"> Last Name </label>
				<input type = "text" id = "lastname" name = "lastname" required="required" value = ""/> <br />
				
				<label for = "newUsername"> Username </label>
				<input class = UN type = "text" id = "newUsername" name = "newUsername" required="required" value = ""/> <br />
				
				<label for = "zipcode"> Zipcode </label>
				<input class = zipcode type = "zipcode" id = "zipcode" name = "zipcode" required="required" value = ""/> <br />
				
				<label for = "newPassword"> Password </label>
				<input class = pw type = "password" id = "newPassword" name = "newPassword" required="required" value = ""/> <br />
				
				<input type = "submit">
			</form>
		</div>
	</div>
	
	</body>
	
</html>