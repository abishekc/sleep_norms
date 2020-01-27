<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<header>
	<h1 id="heading">sleep</h1>
</header>

<div id = "compass">
	<div id = "left">
		<form id="input_param" action="data.php" method="post">

			<span id="age_title">QUESTION 1*</span><br>
			<h2>What is your age?</h2>
			<div id="age_div">
				<input id="age_input" type="text" name="age"><br>
			</div>

			<span id="age_title">QUESTION 2*</span><br>
			<h2>What is your Gender?</h2>
			<div id="gender_div">
				<input type="radio" name="sex" value="male" id="gender_male"/>
				<label for="gender_male"><img src="img/male_v2.png" id="label_male"/></label>

				<input type="radio" name="sex" value="female" id="gender_female"/>
				<label for="gender_female"><img src="img/female_v2.png" id="label_female"/></label>
			</div><br>

			<span id="age_title">QUESTION 3*</span><br>
			<h2>What is your Ethnicity?</h2>
			<select name="ethnicity" id="ethnicity_input">
			    <option value="white">White</option>
			    <option value="black">Black</option>
			    <option value="asian">Asian</option>
			    <option value="hispanic">Hispanic</option>
			    <option value="other">Other</option>
			    <option value="prefer">Prefer Not to Say</option>
			</select><br>

			<input type="submit" value="SUBMIT">
		</form>
	</div>
</div>

<footer>
</footer>

</body>
</html>