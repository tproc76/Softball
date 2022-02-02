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
        <title>Stats Team View and Edit Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<!-- Menu CSS Stuff -->
		<link rel="stylesheet" type="text/css" href="menu_leftside.css">

		<script type="text/javascript" src="menu_leftsideslide.js"></script>
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="manage_stat_teams.js"></script>
	</head>
<body>

<?php
include 'db_setup.php';

$select =mysqli_query($link, "SELECT * FROM seasons ORDER BY year DESC, updated DESC");
?>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="playerheader">Stat Team List</h1>
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
			<th>SEASON</th>
			<th>TEAM NAME</th>
			<th>LEAGUE</th>
			<th>GROUPING</th>
			<th>YEAR</th>
			<th>LAST UPDATED</th>
			<th></th>
		</tr>
	<?php
	while ($row=mysqli_fetch_array($select, MYSQLI_ASSOC)) 
		{
	 ?>
		<tr id="row<?php echo $row['season_id'];?>">
			<td id="team_season<?php echo $row['season_id'];?>"><?php echo $row['season_name'];?></td>
			<td id="team_name<?php echo $row['season_id'];?>"><a href="javascript:void(0)" id="team_link<?php echo $row['season_id'];?>" class="closebtn" onclick="showGames(<?php echo $row['season_id'];?>)"><?php echo $row['team_name'];?></a></td>
			<td id="team_league<?php echo $row['season_id'];?>"><?php echo $row['league'];?></td>
			<td id="team_grouping<?php echo $row['season_id'];?>"><?php echo $row['grouping'];?></td>
			<td id="team_year<?php echo $row['season_id'];?>"><?php echo $row['year'];?></td>
			<td id="team_update<?php echo $row['season_id'];?>"><?php echo $row['updated'];?></td>
			<td>
			<input type='button' class="edit_button" id="edit_button<?php echo $row['season_id'];?>" value="edit" onclick="edit_row('<?php echo $row['season_id'];?>');">
			<input type='button' class="save_button" id="save_button<?php echo $row['season_id'];?>" value="save" onclick="save_row('<?php echo $row['season_id'];?>');" style="display: none;">
			<input type='button' class="delete_button" id="delete_button<?php echo $row['season_id'];?>" value="delete" onclick="delete_row('<?php echo $row['season_id'];?>');">
			</td>
		</tr>
	<?php
		}
	?>
	</table>

</div>
</body>
</html>