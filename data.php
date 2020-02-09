<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php

	//login details - need to updated later.
	$servername = "localhost";
	$username = "sleep";
	$password = "password";
	$db = "myDB";

	$slp_effs = array();
	$ages = array();

	//connection reference is created
	$conn = new mysqli($servername, $username, $password, $db);


	//catch any errors associated with connection interference
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	//form _POST values
	$age = $_POST['age'];
	$sex = $_POST['sex'];
	$ethnicity = $_POST['ethnicity'];
	$weight = $_POST['weight'];
	$height = $_POST['height'];

	//ranges, set
	$ul = $age + 2.5;
	$ll = $age - 2.5;
	$bmi = 703 * ($weight/($height*$height));
	echo($bmi);

	/*function sql_query($age, $sex, $ethnicity) {
		echo("here")
		//set proper gender for candidate
		if($sex != male) {
			$sex = ''
		} else {
			$sex = 1
		}

		if($ethnicity == prefer) {
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male = $sex";
		} else {
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male = 1 AND race = '$ethnicity'"
		}
	}

	sql_query($age, $sex, $ethnicity)*/

	

	/*if($ethnicity == prefer) {
		if($sex == male) {
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male = 1";
		} else {
			$sex = female;
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male != 1";
		}
	} else {
		if($sex == male) {
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male = 1 AND race = '$ethnicity'";
		} else {
			$sex = female;
			$sql = "SELECT age, slp_eff FROM `TABLE 1` WHERE age < $ul AND age > $ll AND male != 1 AND race = '$ethnicity'";
		}
	}*/

	/*$result = $conn->query($sql);

	if ($result) {
	    while ($row = $result->fetch_assoc()) {
	    	array_push($slp_effs, (int)$row["slp_eff"]);
	    	array_push($ages, (int)$row["age"]);
	    }
	} else {
		echo("Error description: " . mysqli_error($conn));
	}

	$average = array_sum($slp_effs)/count($slp_effs);
	$conn->close();*/
?>
<!--<header>
	<h1 id="heading">sleep</h1>
</header>
<div id="summary-div">
<span id="summary">
	For a <?php echo $ethnicity; echo " "; echo $sex; ?> in 
	<?php 
		/*if ($sex == male) {
			echo " his ";
		} else {
			echo " her ";
		}
		echo $age*/
	?>'s, average sleep efficacy is:
	<?php /*echo $average;*/ ?>
</span>
</div>-->
</body>

<!--<canvas id="myChart"></canvas>
<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'doughnut',

	    // The data for our dataset
	    data: {
	        labels: ['Sleep Efficacy'],
	        datasets: [{
	            label: 'Sleep Efficacy',
	            data: [<?php /*echo $average ?>, 100 - <?php echo $average*/ ?>],
	            backgroundColor: [
	            	"#FF6384",
	            	"#FFFFFF"
	            ]
	        }]
	    },

	    // Configuration options go here
	    options: {}
	});
</script>-->
</html>