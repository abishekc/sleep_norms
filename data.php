<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
	//Server login details. Need to be put in a separate config file.
	$servername = "localhost";
	$username = "sleep";
	$password = "password";
	$db = "myDB";

	//Empty arrays required to populate with incomding data.
	$slp_effs = array();
	$ages = array();
	$st1p = array();
	$st2p = array();
	$st34p = array();
	$remp = array();
	$waso = array();
	$slpprdp = array();
	$rem_lat = array();

	//Connection reference to SQL is created.
	$conn = new mysqli($servername, $username, $password, $db);

	//Catch any errors with connection interference.
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

	//Catches error on sql query.
	function sql_query($sql) {
		$result = $conn->query($sql);
		if ($result) {
			return $result;
		} else {
			echo("Error description: " . mysqli_error($conn));
		}
	}

	//Returns square error given the mean and a single value from a set.
	function square_errors($x, $mean) {
		return pow($x - $mean, 2);
	}

	//Provides 'clean' average - i.e. drops NaN or Null values from set before calculation.
	function clean_average($input) {
		$total = 0;
		foreach ($input as $val) {
			if (is_nan($val) != true) {
				$total += $val;
			}
		}

		return ($total/count($input));
	}

	//Provides 'clean' stdev - i.e. drops NaN or Null values from set before calculation.
	function clean_stdev($input, $sample = false) {
		$average = clean_average($input);
		$variance = 0.0;

		foreach ($input as $val){
			$variance += pow($val - $average, 2);
		}

		$variance /= ($sample ? count($input) - 1 : count($input));
		return (float)sqrt($variance);
	}

	echo("st1p: ");
	echo(clean_average($st1p));
	?><br><?php

	echo("st2p: ");
	echo(clean_average($st2p));
	?><br><?php

	echo("st34p: ");
	echo(clean_average($st34p));
	?><br><?php

	echo("remp: ");
	echo(clean_average($remp));
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
	echo(count($slp_effs));
?>
</body>
</html>