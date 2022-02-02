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
        <title>Games View and Edit Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">

		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="attend_games_manage.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		<script type="text/javascript">
			function move_page(teamid)
				{
				$(location).attr('href', 'attendance_home.php?display=teams');
				}
		</script>
	</head>
<body>

<?php
include 'db_setup.php';
	
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

$select = mysqli_query($link, "SELECT * FROM attend_games WHERE team_id='$team_id' ORDER BY game_datetime");
?>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="playerheader">Game List</h1>
	<BR><BR>
</div>	

<br>

<div class="row justify-content-md-center">

	<table id="gametable" border='1' cellspacing='1' cellpadding='5' class='center' >
		<tr>
			<th>DATE</th>
			<th>LOCATION</th>
			<th>OPPONENT</th>
			<th></th>
		</tr>
	<?php
	while ($row=mysqli_fetch_array($select, MYSQLI_ASSOC)) 
		{
	 ?>
		<tr id="row<?php echo $row['game_id'];?>">
<!--			<td id="date<?php echo $row['game_id'];?>"><?php echo $row['game_datetime'];?></td> -->
			<td id="date<?php echo $row['game_id'];?>"><?php $gdatetime = date_create($row['game_datetime']); echo date_format($gdatetime,"M-d-Y G:i");?></td>
			<td id="location<?php echo $row['game_id'];?>"><?php echo $row['location'];?></td>
			<td id="opponent<?php echo $row['game_id'];?>"><?php echo $row['opponent'];?></td>
			<td>
			<input type='button' class="edit_button" id="edit_button<?php echo $row['game_id'];?>" value="edit" onclick="edit_row('<?php echo $row['game_id'];?>');">
			<input type='button' class="save_button" id="save_button<?php echo $row['game_id'];?>" value="save" onclick="save_row('<?php echo $row['game_id'];?>');" style="display: none;">
			<input type='button' class="delete_button" id="delete_button<?php echo $row['game_id'];?>" value="delete" onclick="delete_row('<?php echo $row['game_id'];?>');">
			</td>
		</tr>
	<?php
		}
	?>

		<tr id="new_row">
			 <td><input type="datetime-local" id="new_date"></td>
			 <td><input type="text" id="new_location" maxlength="20"></td>
			 <td><input type="text" id="new_opponent" maxlength="50"></td>
			 <td><input type="button" value="Insert Row" onclick="insert_row();"></td>
		</tr>
	</table>

</div>
<br>
<div class="row justify-content-md-center">
	<input type="button" value="Complete" onclick="move_page(<?php echo $team_id;?>);">
</div>

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