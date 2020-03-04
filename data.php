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
    $print_sex = $_POST['sex'];
	$ethnicity = $_POST['ethnicity'];
	$weight = $_POST['weight'];
	$height = $_POST['height'];
    $metric = $_POST['metric'];

    if($metric == "metric"){
        $weight = $weight * 2.204;
        $height = $height * .3937;
    }

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

		return number_format(($total/$count), 2);
	}

	//Provides 'clean' stdev - i.e. drops NaN or Null values from set before calculation.
	function clean_stdev($input, $sample = false) {
		$average = clean_average($input);
		$variance = 0.0;

		foreach ($input as $val){
			$variance += pow($val - $average, 2);
		}

		$variance /= ($sample ? count($input) - 1 : count($input));
		return number_format((float)sqrt($variance), 2);
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
?>
</head>
<!------- HEADER END -------->

<!------- BODY START -------->
<body>

<div id="compass">
    <div id="nav-wrapper">
        <div id="nav">
            sleep norms
            <img src="./img/moon_one.png" />
        </div>
    </div>
	<div id="upper-wrapper">
        <div id="upper">
            <h2>For a <?php echo($print_sex); ?> between in the ages <?php echo($ll); ?> and <?php echo($ul) ?>, average sleep efficiency is <?php echo((float)clean_average($slp_effs)); ?>%.</h2>
        </div>
    </div>
    
    <div id ="lower-wrapper">
        <div id="lower">
            <div id ="sleep-stages">
                <h1>Sleep Stages</h1>
                <p>
                    <?php include('text/sleep_stage.txt'); ?>
                </p>
                <div id="left-right-wrapper">
                    <div id="left">
                        <h5>STAGE 1</h5>
                        <h1 style="color: #0d47a1;"> 
                            <?php echo((float)clean_average($st1p)); ?>
                        </h1><br>
                        <h5>STAGE 2</h5>
                        <h1 style="color: #1e88e5;">
                            <?php echo((float)clean_average($st2p)); ?>
                        </h1><br>
                        <h5>STAGE 3 & 4</h5>
                        <h1 style="color: #64b5f6;">
                            <?php echo((float)clean_average($st34p)); ?>
                        </h1><br>
                        <h5>REM</h5>
                        <h1 style="color: #e91e63;">
                            <?php echo((float)clean_average($remp)); ?>
                        </h1>
                    </div>
                    <div id="right" style="width: 400px;">
                        <canvas id = "sleep_cycle_chart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
            
            <div id="waso">
                <h1>Wake Time During Sleep</h1>
                <p>
                    <?php include('text/waso.txt'); ?>
                </p>
                <div id="left-right-wrapper">
                    <div id="left">
                        <h5>AVERAGE</h5>
                        <h1><?php echo((float)clean_average($waso)); ?></h1><br>
                        
                        <h5>STANDARD DEVIATION</h5>
                        <h1><?php echo((float)clean_stdev($waso)); ?></h1>
                    </div>
                    <div id="right">
                        <canvas id = "normal_distribution" width="610" height="400"></canvas>
                    </div>
                </div>
                <br>
                <h2>Your results are compiled from <?php echo(count($slp_effs)); ?> subjects.</h2>
            </div>
            
            <div id="slp-eff">
                <h1>Sleep Efficiency</h1>
                <p>
                    <?php include('text/slp_eff.txt'); ?>
                </p>
                <div id="left-right-wrapper">
                    <div id="left">
                        <h5>AVERAGE</h5>
                        <h1><?php echo((float)clean_average($slp_effs)); ?></h1><br>
                        
                        <h5>STANDARD DEVIATION</h5>
                        <h1><?php echo((float)clean_stdev($slp_effs)); ?></h1>
                    </div>
                    <div id="right">
                        <canvas id = "slp_eff_distribution" width="610" height="400"></canvas>
                    </div>
                </div>
            </div>
            
            <div id="slpprdp">
                <h1>Total Sleep Duration</h1>
                <p>
                    <?php include('text/total_dur.txt'); ?>
                </p>
                <div id="left-right-wrapper">
                    <div id="left">
                        <h5>AVERAGE</h5>
                        <h1><?php echo((float)clean_average($slpprdp)); ?></h1><br>
                        
                        <h5>STANDARD DEVIATION</h5>
                        <h1><?php echo((float)clean_stdev($slpprdp)); ?></h1>
                    </div>
                    <div id="right">
                        <canvas id = "slpprdp_distribution" width="610" height="400"></canvas>
                    </div>
                </div>
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
	            	"#0d47a1",
	            	"#1e88e5",
                    "#64b5f6",
                    "#e91e63"
	            ]
	        }]
	    },
	    options: {}
	});
</script>
<script>
    var ctx = document.getElementById('normal_distribution').getContext('2d');
    points = []
    for (x = 0; x < 200; x++){
        points.push({x:x , y: plotNormalWith(x, <?php echo(json_encode(clean_average($waso))); ?>, <?php echo(json_encode(clean_stdev($waso))); ?>)});
    }
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(83,69,211, 0.88)');   
    gradient.addColorStop(1, 'rgba(83,69,211, 0.5)');
    var scatterChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            label: 'WASO',
            data: points,
            backgroundColor: gradient,
            pointRadius: 0,
            borderColor: 'rgb(83,69,211, 1)'
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
    
<script>
    var ctx = document.getElementById('slp_eff_distribution').getContext('2d');
    points = []
    for (x = 30; x < 135; x++){
        points.push({x:x , y: plotNormalWith(x, <?php echo(json_encode(clean_average($slp_effs))); ?>, <?php echo(json_encode(clean_stdev($slp_effs))); ?>)});
    }
    var scatterChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            label: 'Sleep Efficiency',
            data: points,
            backgroundColor: gradient,
            pointRadius: 0,
            borderColor: 'rgb(83,69,211, 1)'
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
    
<script>
    var ctx = document.getElementById('slpprdp_distribution').getContext('2d');
    points = []
    for (x = 40; x < 884; x++){
        points.push({x:x , y: plotNormalWith(x, <?php echo(json_encode(clean_average($slpprdp))); ?>, <?php echo(json_encode(clean_stdev($slpprdp))); ?>)});
    }
    var scatterChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            label: 'Total Sleep Duration',
            data: points,
            backgroundColor: 'rgb(83,69,211, 0.4)',
            pointRadius: 0,
            borderColor: 'rgb(83,69,211, 1)'
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