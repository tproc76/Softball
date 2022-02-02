<?php

include 'db_setup.php';

session_start();
$_SESSION['login_user']="";
$_SESSION['player_id']=-1;
session_unset();
$_SESSION = array();
session_destroy();
?>
<html>
	<head>

	<script type="text/javascript" src="attend_login.js"></script>
	</head>
<body>
	<script type="text/javascript">
		window.addEventListener('load', necessaryCleanup);
	</script>

</body>
</html>