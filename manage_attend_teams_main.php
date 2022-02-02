<?php
	session_start();
	if(isset($_SESSION['login_user']) == false)
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
		exit();
		}
		
	if (($_SESSION['login_user']!="proc@comcast.net") &&
		($_SESSION['login_user']!="timothy.proctor@gm.com"))
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_members_main.php"</script>';
		exit();
		}
?>
<html>
	<head>
        <title>Attend Team View and Edit Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<!-- Menu CSS Stuff -->
		<link rel="stylesheet" type="text/css" href="menu_leftside.css">

		<script type="text/javascript" src="menu_leftsideslide.js"></script>
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="manage_attend_teams.js"></script>
	</head>
<body>

<?php
include 'db_setup.php';

$SQLquery  = "SELECT t.team_id,t.team_name,t.season,p.name, COUNT(g.game_id) as total_games, MAX(g.game_datetime) as last_games, SUM(IF(DATEDIFF(g.game_datetime,CURDATE())>0,1,0)) AS remain_games ";
$SQLquery  .= "FROM attend_team t JOIN attend_players p ON t.creator_id=p.player_id LEFT JOIN attend_games g ON g.team_id=t.team_id GROUP BY team_id";

$select =mysqli_query($link, $SQLquery);
?>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="playerheader">Attendance Team List</h1>
	<BR><BR>
</div>	

<!-- Side MENU Start -->
<?php
	include 'maint_sidemenu.php';
?>
<!-- Side MENU End -->

<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
<div id="main" class="main">		

<br>

<div class="row justify-content-md-center">

	<table id="playertable" border='1' cellspacing='1' cellpadding='5' class='center' >
		<tr>
			<th>TEAM NAME</th>
			<th>SEASON</th>
			<th>CREATOR</th>
			<th>GAMES</th>
			<th style='text-align:center'>REMAINING<br>GAMES</th>
			<th>LAST GAME</th>
			<th></th>
		</tr>
	<?php
	while ($row=mysqli_fetch_array($select, MYSQLI_ASSOC)) 
		{
	 ?>
		<tr id="row<?php echo $row['team_id'];?>">
			<td id="team_name<?php echo $row['team_id'];?>"><?php echo $row['team_name'];?></td>
			<td id="team_season<?php echo $row['team_id'];?>"><?php echo $row['season'];?></td>
			<td id="team_create<?php echo $row['team_id'];?>"><?php echo $row['name'];?></td>
			<td id="team_game<?php echo $row['team_id'];?>" style='text-align:center'><?php echo $row['total_games'];?></td>
			<td id="team_game<?php echo $row['team_id'];?>" style='text-align:center'><?php echo $row['remain_games'];?></td>
			<td id="team_last<?php echo $row['team_id'];?>"><?php echo $row['last_games'];?></td>
			<td>
			<input type='button' class="delete_button" id="delete_button<?php echo $row['team_id'];?>" value="delete" onclick="delete_row('<?php echo $row['team_id'];?>');">
			</td>
		</tr>
	<?php
		}
	?>
	</table>

</div>
</body>
</html>