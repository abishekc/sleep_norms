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
	$st1p = array();
	$st2p = array();
	$st34p = array();
	$remp = array();
	$waso = array();
	$slpprdp = array();
	$rem_lat = array();

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
	$ul = $age + 3;
	$ll = $age - 3;
	$bmi = 703 * ($weight/($height*$height));
	$bmi_ul = $bmi + 3;
	$bmi_ll = $bmi - 3;

	if($sex == "male") {
		$sex = "male = 1";
	} else {
		$sex = "male != 1";
	}

	$conditions = "age > $ll AND age < $ul AND bmi > $bmi_ll AND bmi < $bmi_ul AND $sex";
	#echo($conditions);

	$data = "slp_eff, slpprdp, waso, rem_lat";
	$sleep_stages = "timest1p, timest2p, timest34p, timeremp";

	$sql = "SELECT $data FROM `TABLE 1` WHERE $conditions";
	$sql_two = "SELECT $sleep_stages FROM `TABLE 1` WHERE $conditions";

	$result = $conn->query($sql);

	$result_two = $conn->query($sql_two);

	if ($result_two) {
		while ($row = $result_two->fetch_assoc()) {
			#$total = $row["timest1p"] + $row["timest2p"] + $row["timest34p"] + $row["timesremp"];
			array_push($st1p, $row["timest1p"]);
			array_push($st2p, $row["timest2p"]);
			array_push($st34p, $row["timest34p"]);
			array_push($remp, $row["timeremp"]);
		}
	}

	// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)    
function sd($array) {
    
// square root of sum of squares devided by N-1
return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}

	$st1p_total = 0.0;
	$st1p_stdev = array();
	foreach ($st1p as $val) {
		if (is_nan($val) != true){
			$st1p_total += $val;
			array_push($st1p_stdev, $val);
		}
	}
	echo("st1p: ");
	echo($st1p_total/count($st1p));
	?><br><?php

	$st2p_total = 0.0;
	foreach ($st2p as $val) {
		if (is_nan($val) != true){
			$st2p_total += $val;
		}
	}
	echo("st2p: ");
	echo($st2p_total/count($st2p));
	?><br><?php

	$st34p_total = 0.0;
	foreach ($st34p as $val) {
		if (is_nan($val) != true){
			$st34p_total += $val;
		}
	}
	echo("st34p: ");
	echo($st34p_total/count($st34p));
	?><br><?php

	$remp_total = 0.0;
	foreach ($remp as $val) {
		if (is_nan($val) != true){
			$remp_total += $val;
		}
	}
	echo("remp: ");
	echo($remp_total/count($remp));
	?><br><?php

	if ($result) {
	    while ($row = $result->fetch_assoc()) {
	    	array_push($slp_effs, (int)$row["slp_eff"]);
	    	array_push($ages, (int)$row["age"]);
	    }
	} else {
		echo("Error description: " . mysqli_error($conn));
	}
	echo("subjects: ");
	echo(count($slp_effs))

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