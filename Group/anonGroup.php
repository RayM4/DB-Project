<?php
$link = new mysqli("localhost", "raym4", "", "meetup");
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}


$interest = $_POST['interest'];

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

$query4 = $link->prepare("SELECT * FROM about") or die(mysqli_error($link));
$query4->execute();
$query4->store_result();
$rowsAbout = $query4->num_rows;
$query4->bind_result($iid, $gid);

$aboutData = array(array());

$i = 0;
while ($query4->fetch()) {
	$aboutData[$i] = array($iid, $gid);
	$i++;
}

//find array of groupNames based on interest name
function findGroupIDs($interestName) {
	global $aboutData;
	global $rowsAbout;
	$data = array();
	$count = 0;
	for ($i = 0; $i < $rowsAbout; $i++) {
	    //echo $aboutData[$i][0]." ,".$interestName."|";
		if (strcmp($aboutData[$i][0],$interestName) == 0) {
			$data[$count] = $aboutData[$i][1];
			$count++;
		} 
	}
	return $data;
}
//search by group id and return index
function findGroupIdxById($num) {
    global $groupData;
    global $rowGroups;
    for ($i = 0; $i < count($groupData); $i++) {
        if ($groupData[$i][0] == $num) {
            return $i;
        }        
    }
    return -1;
}

?>

<!DOCTYPE html>
<html>
	<head>
	    <title> Stuff </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
 	    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../CSS Files/anon.css">
	</head>
	
	<body>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = banner> Meetup </div>
			</div>
		</row>
		</div>
		<?php
	    echo "<h1 align = 'center'>Groups with Interest: ".$interest."</h1> </br>";
	    ?>
		<div class="row-fluid">
			<div class="col-lg-1"></div>
			<div class="col-lg-3">Group Name
				<div class = data>
					<?php
				    $ids = findGroupIDs($interest);
				    //echo count($ids);
				    for ($i = 0; $i < count($ids); $i++) {
				        $index = findGroupIdxById($ids[$i]);
				        if ($index != -1) { //print mroe stuff here if u need to
				            echo "<span><div>".$groupData[$index][1]."</div> </span></br>";
				        }
				    }
				    ?>
			    </div>
			</div>
			<div class="col-lg-4">Group Description
				<div class = data1>
					<?php
				    $ids = findGroupIDs($interest);
				    for ($i = 0; $i < count($ids); $i++) {
				        $index = findGroupIdxById($ids[$i]);
				        if ($index != -1) { //print mroe stuff here if u need to
				            echo "<span>".$groupData[$index][2]."</span></br>";
				        }
				    }
				    ?>
			    </div>
			</div>
			
			<div class="col-lg-3">Group Leader
				<div class = data2>
					<?php
				    $ids = findGroupIDs($interest);
				    for ($i = 0; $i < count($ids); $i++) {
				        $index = findGroupIdxById($ids[$i]);
				        if ($index != -1) { //print mroe stuff here if u need to
				            echo "<span>".$groupData[$index][3]."</span></br>";
				        }
				    }
				    ?>
			    </div>
			</div>
			<div class="col-lg-1"></div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class = "row"> <a href = "../homepage.php" class="btn btn-danger" role="button"> Home </a> </div>
			</div>
		</row>
		</div>
		</div>
	</body>
</html>