<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
	<h1 id="heading">sleep</h1>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script>
        /* check if DOM is read before executing unit update */
        $(document).ready(function() {
            $('#unit_input').change(function () {
                var value = this.value;
                if (value == "imperial"){
                    console.log("imperial updated");
                    $('#weight_heading').text("What is your weight? (lbs)");
                    $('#height_heading').text("What is your height? (inches)");
                } else if(value == "metric"){
                    console.log("metric updated");
                    $('#weight_heading').text("What is your weight? (kgs)");
                    $('#height_heading').text("What is your height? (cm)");
                }
            });
        });
    </script>
</header>

<div id = "compass">
	<div id = "left">
		<form id="input_param" action="data.php" method="post">
            <h2>What is your ethnicity?</h2>
			<select name="ethnicity" id="ethnicity_input">
			    <option value="white">White</option>
			    <option value="black">Black</option>
			    <option value="asian">Asian</option>
			    <option value="hispanic">Hispanic</option>
			    <option value="other">Other</option>
			    <option value="prefer">Prefer Not to Say</option>
			</select><br>
            
			<h2>What is your age?</h2>
			<div id="age_div">
				<input id="age_input" type="text" name="age"><br>
			</div>

			<h2>What is your sex?</h2>
			<div id="gender_div">
				<input type="radio" name="sex" value="male" id="gender_male"/>
				<label for="gender_male"><img src="img/male_v2.png" id="label_male"/></label>

				<input type="radio" name="sex" value="female" id="gender_female"/>
				<label for="gender_female"><img src="img/female_v2.png" id="label_female"/></label>
			</div><br>

			<h2 id="weight_heading">What is your weight? (kg)</h2>
			<div id="weight_div">
				<input id="age_input" type="text" name="weight"><br>
			</div>

			<h2 id="height_heading">What is your height? (cm)</h2>
			<div id="height_div">
				<input id="age_input" type="text" name="height"><br>
			</div>

			<select name="metric" id="unit_input">
			    <option value="metric">metric</option>
			    <option value="imperial">imperial</option>
			</select><br>

			<input type="submit" value="SUBMIT">
		</form>
	</div>
</div>

<footer>
</footer>

</body>
</html>
