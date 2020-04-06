<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script>
        /* check if DOM is read before executing unit update */
        $(document).ready(function() {
            $('#height_two').hide()
            $('#unit_input').change(function () {
                var value = this.value;
                if (value == "imperial"){
                    console.log("imperial updated");
                    $('#weight_units').text("POUNDS");
                    $('#height_units').css("float", "left");
                    $('#height_units').text("FEET");
                    $('#inches_label').text("INCHES");
                    $('#height_two').show();
                } else if(value == "metric"){
                    console.log("metric updated");
                    $('#weight_units').text("KILOGRAMS");
                    $('#height_units').text("CENTIMETERS");
                    $('#height_units').css("float", "none");
                    $('#inches_label').text("");
                    $('#height_two').hide();
                }
            });
        });
    </script>
    <div id="nav-wrapper">
        <div id="nav">
            sleep norms
            <img src="./img/moon_one.png" />
        </div>
    </div>
</header>

<div id = "compass">
    <div id = "inner">
	<div id = "left">
		<form id="input_param" action="data.php" method="post">            
			<h2 style="margin-bottom:10px;">What is your age?</h2>
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
            
            <h2>What are your preferred units?</h2>
            <select name="metric" id="unit_input">
			    <option value="metric">metric</option>
			    <option value="imperial">imperial</option>
			</select><br>

			<h2 id="weight_heading">What is your weight?</h2>
            <h3 id="weight_units">KILOGRAMS</h3>
			<div id="weight_div">
				<input id="age_input" type="text" name="weight"><br>
			</div>

			<h2 id="height_heading">What is your height?</h2>
            <h3 id="height_units">CENTIMETERS</h3>
            <h3 id="inches_label"></h3>
			<div id="height_div">
				<input id="age_input" type="text" name="height">
                <input id="height_two" type="text" name="height_two">
			</div>
            
            <h2>What is your ethnicity?</h2>
			<select name="ethnicity" id="ethnicity_input">
                <option value="prefer"></option>
			    <option value="white">White</option>
			    <option value="black">Black</option>
			    <option value="asian">Asian</option>
			    <option value="hispanic">Hispanic</option>
			    <option value="other">Other</option>
			</select><br>

			<input type="submit" value="SUBMIT">
		</form>
	</div>
    <div id="right">
    </div>
    </div>
</div>
<div id="footer-wrapper">
    <div id="footer-inner">
        <div id="footer">
            <span style="color: #5345d3; font-size: 22px;">
                Center for Human Sleep Science
            </span>
            <br>
            University of California, Berkeley
            <br><br><br><br>
            Copyright 2020
        </div>
        
        <div id="footer-right">
            <img id="berk_logo" src="img/berk_white.png"/>
        </div>
    </div>
</div>

</body>
</html>
