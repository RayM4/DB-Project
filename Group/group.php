<?php
session_start();
$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$query = $link->prepare("SELECT * FROM groups WHERE group_id NOT IN 
(SELECT groups.group_id FROM groups join belongs_to ON groups.group_id = belongs_to.group_id 
WHERE belongs_to.username = ?)") or die(mysqli_error($link));
$query->bind_param('s', $_SESSION['username']);
$query->execute();
$query->store_result();
$rowsGroups = $query->num_rows;
$query->bind_result($gid, $gName, $desc, $leader);

$groupData = array(array());

$i = 0;
while ($query->fetch()) {
	$groupData[$i] = array($gid, $gName, $desc, $leader);
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

$_SESSION['lastLoc'] = "group";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Create Group</title>
  		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../CSS Files/group.css">
        <script src = "../Javascript/group.js"></script>
	</head>
	
	<body>
	<!--startpage-->
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = banner> Create a group </div>
			</div>
		</row>
		</div>
		<!--Navbar-->
		<nav class="navbar navbar-default">
			<div class = "col-md-3 text-center"> <a href = "../homepage.php" class="btn btn-danger" role="button"> Home </a> </div>
			<div class = "col-md-3 text-center"> <a href = "group.php" class="btn btn-primary" role="button"> Group </a> </div>
			<div class = "col-md-3 text-center"> <a href = "../Event/event.php" class="btn btn-info" role="button"> Create Event </a> </div>
			<div class = "col-md-3 text-center"> <a href = "../logout.php" class="btn btn-warning" role="button"> Logout </a> </div>
		</nav>


		<div class = "row-fluid"> <!-- style = "display:none;" -->
			<div class = "col-lg-1"></div>
			<div class = "col-lg-5">
				<div class = content>
					<h3> Create Group </h3>
					<form name = "create_event" action = "createGroup.php" method = "POST">
						<label for = "groupName"> Group Name </label>
						<input class = groupName type = "text" id = "groupName" name = "groupName" required="required" value = ""/> <br />
						
						<label for = "description"> Description </label>
						<input class = desc type = "text" id = "description" name = "description" required="required" value = ""/> <br />
			
		        		<div class="row">
					 		<div class="btn-group">
					  			<button id = "bttn" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Interest
					  				
					  			</button>
					  			<ul id = "Interest" class="dropdown-menu" role="menu" >
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
							
						<div class = hiddenBoxes hidden>
							<input type = textbox class = inputbox name = groupInterest id = "box1">  <br />
						</div>
						
						<input type = "submit" class = submit>	
					</form>
					
					<form name = "create_Interest" action = "createInterest.php" method = "POST"> <br/><br /><br /><br /><br />
					    <label for = "interestName Interest"> Don't see the Interest you need? Please Enter An Interest </label><br />
					    <input type = textbox class = inputbox name = interestName id = nInterst>
					    <input type = "submit" class = submit>
					</form>
				</div>
			</div>
			<div class = "col-lg-5">
				<div class = groups>
					<h1>Groups you can join</h1></br>
					<?php
  					for ($i = 0; $i < $rowsGroups; $i++ ) {
  						echo "<form action = 'joinGroup.php' method = 'POST'> <input hidden name = group_id value = ".$groupData[$i][0].">";
  						echo "<span>".$groupData[$i][1]." - ".$groupData[$i][2]."<button class = join type = submit> Join </button></span>";
  						echo "<span> Leader: ".$groupData[$i][3]."</span></form></br></br>";
  						}
  					?>	
				</div>
			</div>
			<div class = "col-lg-1"></div>
		</div>
	</body>
</html>