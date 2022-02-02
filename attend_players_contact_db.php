<?php
include 'db_setup.php';

if(isset($_POST['edit_row']))
{
	$pid=$_POST['row_id'];
	$phone=$_POST['name_phone'];
	
	mysqli_query($link, "UPDATE attend_players SET phone='$phone' WHERE player_id='$pid'");
	echo "success";
	exit();
}
?>
