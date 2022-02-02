<?php
	session_start();
	if(isset($_SESSION['login_user']) == false)
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
		exit();
		}
?>
<html>
	<head>
        <title>Player View and Edit Page</title>
	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">
		
		<!-- The follow functions are used/required by the load function in attend_members.js -->
		<script type="text/javascript">
		function getSessionPlayerId()
			{
			var playerId = <?php echo $_SESSION['player_id'] ?>;
			return playerId;
			}
		function getSessionPlayerEmail()
			{
			var playerEmail = "<?php echo $_SESSION['login_user'] ?>";
			return playerEmail;
			}
		function getSessionPlayerName()
			{
			var playerName = "<?php echo $_SESSION['full_name'] ?>";
			return playerName;
			}
		</script>	
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		<script type="text/javascript" src="attend_email.js"></script>
		<script type="text/JavaScript" src="attend_members.js"></script>
	</head>
<body>

<?php
include 'db_setup.php';

$create_new = false;

if(isset($_GET['team']))
	{
	$team_id = $_GET['team'];
	$_SESSION['team_id'] = $team_id;
	}
else
	{
	echo '<script type="text/javascript">window.location = "attendance_home.php?display=teams"</script>';	
	exit();
	}

if(isset($_GET['create']))
	{
	$create_new = true;
	}
	
$result = mysqli_query($link, "SELECT * FROM attend_team WHERE team_id='$team_id'");
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$TeamName = $row['team_name'];
?>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="playerheader">Team Member List
	<BR>	
<?php
echo $TeamName . "<br>";
?>
</h1>
<br>
</div>	

<br>

<div class="row justify-content-md-center">

	<div class="row justify-content-md-center">
		<table id="playertable" border='1' cellspacing='1' cellpadding='5' class='center' >
			<tr>
				<th>SUB*</th>
				<th>EMAIL</th>
				<th></th>
				<th>FULL NAME</th>
				<th>MANAGER</th>
				<th></th>
			</tr>
			
		<?php 
			for ($row=1; $row <=15; $row++ )
			{
		?>
			<tr id="row<?php echo $row ?>" bgcolor="#FFFFFF">
				<td style='text-align:center'><input type="checkbox" name="subcheckBox<?php echo $row ?>" id="subcheckBox<?php echo $row ?>" onclick="checkSub(<?php echo $row ?>)"></td>
				<td id="email<?php echo $row ?>"><input type="text" name="name_email<?php echo $row ?>" id="in_email<?php echo $row ?>"></td>
				<td id="find<?php echo $row ?>">
					<input type='button' class="find_button" id="find_button<?php echo $row ?>" value="Find" onclick="find_email('<?php echo $row ?>');">
				</td>
				<td id="name_full<?php echo $row ?>"><input type="text" name="name_full<?php echo $row ?>" id="in_name<?php echo $row ?>"></td>
				<td style='text-align:center'><input type="checkbox" name="checkBox<?php echo $row ?>" id="checkBox<?php echo $row ?>" onclick="checkManager(<?php echo $row ?>)"></td>
				<td style='text-align:center'><input type="button" value="Cut" onclick="deleteRow(<?php echo $row ?>)"></td>
			</tr>
		<?php
			}
		?>
			<tr>
				<td colspan="6" style="text-align:center"><input type="submit" value="Save Team List" name="submit" onclick="verfyMemberEntries(<?php echo $team_id . "," . $create_new ?>)"></td>
			</tr>
		</table>
	</div>		
<!-- /form--> 
</div>
<div class="row justify-content-md-center">
		* Subs are not listed after regulars
</div>
<input type='hidden' id='table_count' value='15'>

<div class="footer">
	<?php  
	$fullfilename = $_SERVER['REQUEST_URI']; 
	$fileparts = explode("?",basename($fullfilename));
	$filename = $fileparts[0];
	//echo $filename;
	//echo filemtime($filename);
	
	date_default_timezone_set('US/Eastern');
	// checking last time the contents of
	// a file were changed and formatting
	// the output of the date 
	//echo "Version:".date("y.md.Hi", filemtime($filename));
	echo "Updated: ".date("F d Y H:i:s", filemtime($filename));
	?>
</div>

</body>
</html>
