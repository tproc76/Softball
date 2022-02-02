<!DOCTYPE html>
<html>

    <head>
        <title>Password Reset Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        
		<style type="text/css">
		   .centerText{
			   text-align: center;
			}
		</style>
		
		<script type="text/javascript" src="sorttable.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		<script type="text/javascript" src="attend_login.js"></script>
		<script type="text/javascript">
			function updatePassword()
				{
				var pwd1 = document.getElementById("in_pass1");
				var pwd2 = document.getElementById("in_pass2");
				
				if (pwd1.value.length == 0)
					{
					var feed = document.getElementById("out_feedback");
					feed.innerHTML = "<font color='red'>Passwords Can Not Be Blank</font>";
					return;
					}
				
				if (pwd1.value != pwd2.value)
					{
					var feed = document.getElementById("out_feedback");
					feed.innerHTML = "<font color='red'>Passwords Do Not Match</font>";
					return;
					}
					
				passHash = SHA256(pwd1.value);
				resetId = getQueryVariable("reset");
				
				$.ajax
					({
					type:'post',
					url:'attend_login.php',
					data:
						{
						reset:'reset',
						resetID:resetId,
						pwd:passHash
						},
					success:function(response) 
						{
						var parts = response.split(":");
						var feedback = document.getElementById("out_feedback");
						if(parts[0]=="success")
							{
							$(location).attr('href', 'attend_login.html');
							}
						else if(parts[0]=="fail")
							{
							feedback.innerHTML = "<font color='red'>" + parts[1] + "</font>";
							}
						else
							{					
							feedback.innerHTML = response;
							}
						}
					});
				}
		</script>
		
    </head>
    <!-- slide-toggle-menu -->
    <body>
        <!--top-header-->
		<div class="row justify-content-md-center">
			<h1 id="attendheader">Password Reset</h1>
			<BR><BR>
		</div>	
				
		<br>
				
		<!-- Outer Table - Same as "Attenace home" page layout -->
		<div class="row justify-content-md-center">
			<table width="90%" border='0' cellspacing='1' cellpadding='10' class='center' id='headertable'>
				<col width="20%">
				<col width="60%">
				<col width="20%">
				<tr><td style="vertical-align:top"></td>		
					<div class="row justify-content-md-center">
					<table id="edittable" border="1" cellspacing="1" cellpadding="20" class="center">
						<tr id="row_password"><td id="lbl_password">New Password<br><br>Re-Enter Password</td>
							<td id="input_pass"><input type="password" name="pass" id="in_pass1"><br><br>
											    <input type="password" name="pass" id="in_pass2"></td></tr>
						<tr><td colspan="2" style="text-align:center"><button type="button" onclick="updatePassword()">Update Password</button><br><br>
											<h4 id="out_feedback"></h4>
					</table></div>
				</tr>
			</table>
		</div>
		<BR><BR>
						
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="dist-js/popper.min.js"></script>
		<script type="text/javascript" src="dist-js/tether.min.js"></script>

    </body>
</html>
