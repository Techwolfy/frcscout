<?php
	if($_GET["action"] == "record") {
		date_default_timezone_set("America/Los Angeles");
		$_POST["timestamp"] = date("Y-m-d h:i:s A");
		$datafile = fopen("scouting.csv", "a");
		fputcsv($datafile, $_POST);
		fclose($datafile);
		header("Location: ?action=menu&teamNum=$_POST[teamNum]&roundNum=$_POST[roundNum]");
		die();
	}
?>

<html>

<head>
	<title>Team 691 Scouting</title>
	<link href="http://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
	<style>
		body {
			background-color:#222222;
			color:#DDDDDD;
			font-family:"Roboto",sans-serif;
		}
	</style>
</head>

<body>

<?php

$locale = array(
	array("Team Number", "teamNum"),
	array("Round Number", "roundNum"),
	array("Alliance Color", "allianceColor"),
	array("Alliance Score", "allianceScore"),
	array("Autonomous", "autonomous"),
	array("Teleop", "teleop"),
	array("Drive", "drive"),
	array("Omnidirectional Drive", "driveOmni"),
	array("Drive Speed", "driveSpeed"),
	array("Drive Power", "drivePower"),
	array("Totes", "totes"),
	array("Containers", "containers"),
	array("Litter", "litter"),
	array("Focus", "focus"),
	array("Stack Height", "stackHeight"),
	array("Ground Pickup", "groundPickup"),
	array("Human Pickup", "humanPickup"),
	array("Comments", "comments"),
	array("Timestamp","timestamp")
);

$addform = <<<EOT
<form action="?action=record" method="post">
{$locale[0][0]}: <input name="{$locale[0][1]}" type="number" min="0" step="1" required><br>
{$locale[1][0]}: <input name="{$locale[1][1]}" type="number" min="0" step="1" required><br>
{$locale[2][0]}:
<select name="{$locale[2][1]}" required>
	<option selected disabled hidden></option>
	<option value="blue">Blue</option>
	<option value="red">Red</option>
</select><br>
{$locale[3][0]}: <input name="{$locale[3][1]}" type="number" min="0" step="1" required><br>
{$locale[4][0]}: <input name="{$locale[4][1]}" type="radio" value="true" required>Yes<input name="{$locale[4][1]}" type="radio" value="false" required>No<br>
{$locale[5][0]}: <input name="{$locale[5][1]}" type="radio" value="true" required>Yes<input name="{$locale[5][1]}" type="radio" value="false" required>No<br>
{$locale[6][0]}: <input name="{$locale[6][1]}" type="radio" value="true" required>Yes<input name="{$locale[6][1]}" type="radio" value="false" required>No<br>
{$locale[7][0]}: <input name="{$locale[7][1]}" type="radio" value="true" required>Yes<input name="{$locale[7][1]}" type="radio" value="false" required>No<br>
{$locale[8][0]}:
<select name="{$locale[8][1]}" required>
	<option selected disabled hidden></option>
	<option value="none">None</option>
	<option value="slow">Slow</option>
	<option value="medium">Medium</option>
	<option value="fast">Fast</option>
</select><br>
{$locale[9][0]}:
<select name="{$locale[9][1]}" required>
	<option selected disabled hidden></option>
	<option value="none">None</option>
	<option value="low">Low</option>
	<option value="medium">Medium</option>
	<option value="high">High</option>
</select><br>
{$locale[10][0]}: <input name="{$locale[10][1]}" type="radio" value="true" required>Yes<input name="{$locale[10][1]}" type="radio" value="false" required>No<br>
{$locale[11][0]}: <input name="{$locale[11][1]}" type="radio" value="true" required>Yes<input name="{$locale[11][1]}" type="radio" value="false" required>No<br>
{$locale[12][0]}: <input name="{$locale[12][1]}" type="radio" value="true" required>Yes<input name="{$locale[12][1]}" type="radio" value="false" required>No<br>
{$locale[13][0]}:
<select name="{$locale[13][1]}" required>
	<option selected disabled hidden></option>
	<option value="none">None</option>
	<option value="low">Totes</option>
	<option value="medium">Containers</option>
	<option value="high">Litter</option>
</select><br>
{$locale[14][0]}: <input name="{$locale[14][1]}" type="number" min="0" step="1" required><br>
{$locale[15][0]}: <input name="{$locale[15][1]}" type="radio" value="true" required>Yes<input name="{$locale[15][1]}" type="radio" value="false" required>No<br>
{$locale[16][0]}: <input name="{$locale[16][1]}" type="radio" value="true" required>Yes<input name="{$locale[16][1]}" type="radio" value="false" required>No<br>
{$locale[17][0]}: <input name="{$locale[17][1]}" type="text"><br>
<input type="submit">
</form>
EOT;

	if($_GET["action"] == "add") {
		echo $addform;
	} else if($_GET["action"] == "view") {
		$data = array();
		$datafile = fopen("scouting.csv", "r");
		while(!feof($datafile)) {
			$data[] = fgetcsv($datafile);
		}
		fclose($datafile);
		$result = array();
		if(isset($_GET["teamNum"])) {
			if(isset($_GET["roundNum"])) {
				//Return row
				$i = 0;
				foreach($data as $record) {
					if($record[0] == $_GET["teamNum"] && $record[1] == $_GET["roundNum"]) {
						if($i > 0) {
							echo "<p>\nIf you're seeing this, someone screwed up. I hate users. Go away.\n</p>\n";
						}
						$i++;
						echo "Team: {$_GET[teamNum]}<br>\n";
						echo "Round: {$_GET[roundNum]}<br>\n";
						foreach($record as $key => $value) {
							echo $locale[$key][0].": ".$value."<br>\n";
						}
					}
				}
			} else {
				//Return rounds
				foreach($data as $record) {
					if($record[0] == $_GET["teamNum"]) {
						$result[] = $record[1];
					}
				}
				echo "Team: {$_GET[teamNum]}<br>\n";
				echo "Select a round:<br>\n";
				foreach(array_unique($result) as $round) {
					echo "<a href=\"?action=view&teamNum={$_GET[teamNum]}&roundNum=$round\">Round $round</a><br>\n";
				}
			}
		} else {
			//Return teams
			foreach($data as $record) {
				if($record[0] != null) {
					$result[] = $record[0];
				}
			}
			echo "Select a team:<br>\n";
			foreach(array_unique($result) as $team) {
				echo "<a href=\"?action=view&teamNum=$team\">Team $team</a><br>\n";
			}
		}
	} else if($_GET["action"] == "get") {
		echo file_get_contents("scouting.csv");
	} else if($_GET["action"] == "menu") {
		echo "<h1>\n";
		echo "<a href=\"?action=add\">Add Record</a><br>\n";
		echo "<a href=\"?action=view\">View Records</a><br>\n";
		echo "<strong>Record added for team $_GET[teamNum] in round $_GET[roundNum]!</strong>";
		echo "</h1>\n";
	} else {
		echo "<h1>\n";
		echo "<a href=\"?action=add\">Add Record</a><br>\n";
		echo "<a href=\"?action=view\">View Records</a><br>\n";
		echo "</h1>\n";
	}
?>

</body>

</html>
