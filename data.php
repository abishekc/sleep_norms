<!DOCTYPE HTML>
<html>

<!------- HEADER START -------->
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/6.6.0/math.min.js"></script>
<link rel="stylesheet" type="text/css" href="data.css">
    
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
	$ul = $age + 5;
	$ll = $age - 5;
	$bmi = 703 * ($weight/($height*$height));
	$bmi_ul = $bmi + 3;
	$bmi_ll = $bmi - 3;

    //Set $sex variable based on male or female input.
	if($sex == "male") {
		$sex = "male = 1";
	} else {
		$sex = "male != 1";
	}

    //Prepare conditions and select statement data.
	$conditions = "age > $ll AND age < $ul AND bmi > $bmi_ll AND bmi < $bmi_ul AND $sex";

	$data = "slp_eff, slpprdp, waso, rem_lat";
	$sleep_stages = "timest1p, timest2p, timest34p, timeremp";

	$sql = "SELECT $data FROM `TABLE 1` WHERE $conditions";
	$sql_two = "SELECT $sleep_stages FROM `TABLE 1` WHERE $conditions";

    //Run sql queries on database and fetch data.
	$result = $conn->query($sql);
	$result_two = $conn->query($sql_two);

    //
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
        $count = 0;
		foreach ($input as $val) {
			if (is_nan($val) != true) {
				$total += $val;
                $count += 1;
			}
		}

		return ($total/$count);
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

	$sleep_stages = array();
	array_push($sleep_stages, clean_average($st1p));
	array_push($sleep_stages, clean_average($st2p));
	array_push($sleep_stages, clean_average($st34p));
	array_push($sleep_stages, clean_average($remp));

	if ($result) {
	    while ($row = $result->fetch_assoc()) {
	    	array_push($slp_effs, (int)$row["slp_eff"]);
	    	array_push($ages, (int)$row["age"]);
            array_push($waso, (int)$row["waso"]);
            array_push($slpprdp, (int)$row["slpprdp"]);
	    }
	} else {
		echo("Error description: " . mysqli_error($conn));
	}
    
	echo("subjects: ");
	echo(count($slp_effs));
?>
</head>
<!------- HEADER END -------->

<!------- BODY START -------->
<body>

<div id="compass">
	<div id="upper-wrapper">
        <div id="upper">
            <div id="button_one">Sleep Stages
                <canvas id = "sleep_cycle_chart" width="400" height="400"></canvas>
            </div>
            <div id="button_two">
                Wake Time During Sleep
                <h1><?php echo((float)clean_average($waso)); ?></h1>
            </div>
            <div id="button_three">
                Sleep Efficiency<br>
                <h1><?php echo((int)clean_average($slp_effs)); ?></h1>
            </div>
            <div id="button_four">
                Total Sleep Time<br>
                <h1><?php echo((int)clean_average($slpprdp)); ?></h1>
            </div>
        </div>
    </div>
    
    <div id ="lower-wrapper">
        hello
        <div id="lower">
            <div id ="graph">
                <canvas id = "normal_distribution" width="1200" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    function stdNormalDistribution (x) {
        return Math.pow(Math.E,-Math.pow(x,2)/2)/Math.sqrt(2*Math.PI);
    }
    
    function plotNormalWith(x, mu, stdev) {
        return (1 / (stdev*Math.sqrt(2*Math.PI))) * 
                (Math.pow(
                    Math.E, 
                    ((-0.5)*Math.pow(((x - mu)/(stdev)), 2))
                ))
    }
</script>

<!------- GRAPH OPTIONS START ------->
<script>
	var context = document.getElementById('sleep_cycle_chart').getContext('2d');
	var sleep_cycle_chart = new Chart(context, {
	    type: 'doughnut',
	    data: {
	        labels: ['Stage 1', "Stage 2", "Stage 3/4", "REM"],
	        datasets: [{
	            data: <?php echo(json_encode($sleep_stages)) ?>,
	            backgroundColor: [
	            	"#FF6384",
	            	"#FF6384",
                    "#FF6384",
                    "#FF6384"
	            ]
	        }]
	    },
	    options: {}
	});
</script>
<script>
    var ctx = document.getElementById('normal_distribution').getContext('2d');
    points = []
    for (x = -30; x < 30; x++){
        points.push({x:x , y: plotNormalWith(x, <?php echo(json_encode(clean_average($waso))); ?>, <?php echo(json_encode(clean_stdev($waso))); ?>)});
    }
    var scatterChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            label: 'Scatter Dataset',
            data: points
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: false
                },
                type: 'linear',
                position: 'bottom'
            }]
        }
    }
});
</script>
<!------- GRAPH OPTIONS END ------->
    
</body>
<!------- BODY END -------->
</html>