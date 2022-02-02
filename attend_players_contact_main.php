<?php
	session_start();
	if(isset($_SESSION['login_user']) == false)
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
		exit();
		}
		
	if (isset($_SESSION['team_id']))
		{
		$teamid = $_SESSION['team_id'];
		}

	include 'db_setup.php';	
?>

<html>
	<head>
        <title>Player Contact Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">

		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="attend_players_contact.js"></script>
		<script type="text/javascript">
			function move_page()
				{
				$(location).attr('href', 'attendance_home.php?display=teams');
				}
		</script>
	</head>
<body>

<?php
$select =mysqli_query($link, "SELECT * FROM attend_players p JOIN attend_members m ON p.player_id=m.player_id WHERE m.team_id=$teamid ");
?>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="playerheader">Player Contact List</h1>
	<BR><BR>
</div>	


<br>

<div class="row justify-content-md-center">

	<table id="playertable" border='1' cellspacing='1' cellpadding='5' class='center' >
		<tr>
			<th>EMAIL</th>
			<th>FULL NAME</th>
			<th>PHONE</th>
			<th></th>
		</tr>
	<?php
	while ($row=mysqli_fetch_array($select, MYSQLI_ASSOC)) 
		{
	 ?>
		<tr id="row<?php echo $row['player_id'];?>">
			<td id="name_email<?php echo $row['player_id'];?>"><?php echo $row['email'];?></td>
			<td id="name_full<?php echo $row['player_id'];?>"><?php echo $row['name'];?></td>
			<td id="phone<?php echo $row['player_id'];?>"><?php echo $row['phone'];?></td>
			<td>
			<input type='button' class="edit_button" id="edit_button<?php echo $row['player_id'];?>" value="edit" onclick="edit_phonerow('<?php echo $row['player_id'];?>');">
			<input type='button' class="save_button" id="save_button<?php echo $row['player_id'];?>" value="save" onclick="save_phonerow('<?php echo $row['player_id'];?>');" style="display: none;">
			</td>
		</tr>
	<?php
		}
	?>

	</table>

</div>
<br>
<div class="row justify-content-md-center">
	<input type="button" value="Exit" onclick="move_page();">
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