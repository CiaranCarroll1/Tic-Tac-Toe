<?php session_start(); ?>

<!DOCTYPE html>
<html charset="utf-8" lang="en-ie">
<head>
    <title>Tic-Tac-Toe</title>
    <link rel="stylesheet" href="styles.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
		$(document).ready( function() {
			var login = false;
			var register = false;
			
			$.ajaxSetup({ cache: false });
			
			$("#login").click( function() {
				if(!login) {
					if(register) {
						$("#registerFrmDiv").removeClass("showDiv").addClass("hideDiv");
						register = false;
					}
					$("#loginFrmDiv").removeClass("hideDiv").addClass("showDiv");
					$("#uname").focus();
					$("#menu").css("height", $("#content").css("height"));
					login = true;
				} else {
					$("#loginFrmDiv").removeClass("showDiv").addClass("hideDiv");
					$("#menu").css("height", $("#content").css("height"));
					login = false;
				}
			}); 
			
			$("#register").click( function() {
				if(!register) {
					if(login) {
						$("#loginFrmDiv").removeClass("showDiv").addClass("hideDiv");
						login = false;
					}
					$("#registerFrmDiv").removeClass("hideDiv").addClass("showDiv");
					$("#uname").focus();
					$("#menu").css("height", $("#content").css("height"));
					register = true;
				} else {
					$("#registerFrmDiv").removeClass("showDiv").addClass("hideDiv");
					$("#menu").css("height", $("#content").css("height"));
					register = false;
				}
			});
			
			$("#loginSub").click(function() {
				un = $("#loginFrm #uname").val();
				pw = $("#loginFrm #pword").val();
				
				$.ajax({
					method: "POST",
					url: "action/login.php",
					data: {uname: un, pword: pw},
					cache: false,
					success: function (result) {
						if(result < 0) {
							alert("Apologies. \nThere is some problem connecting to the WebService. \nPlease try again later.");
						}
						if(result == 0) {
							alert("Incorrect username and/or password.\nPlease re-enter your details or register an account.");
						}
						if(result > 0) {
							moveToSecure(result, un);
						}
					}
				});
			});
			
			$("#registerSub").click(function() {
				un = $("#registerFrm #uname").val();
				pw = $("#registerFrm #pword").val();
				pw2 = $("#registerFrm #pword2").val();
				fn = $("#registerFrm #fname").val();
				sn = $("#registerFrm #sname").val();
				
				if(checkFields()) {
					$.ajax({
					method: "POST",
					url: "action/register.php",
					data: {username: un, password: pw, name: fn, surname: sn},
					cache: false,
					success: function (result) {
						if(result < 0) {
							alert("Apologies. \nThere is some problem connecting to the WebService. \nPlease try again later.");
						}
						if(result == 0) {
							alert("Problem registering those details.\nPlease re-enter your details.");
						}
						if(result > 0) {
							moveToSecure(result, un);
						}
					}
				});
				}
			});
			
			function checkFields() {
				un = $("#registerFrm #uname").val();
				pw = $("#registerFrm #pword").val();
				pw2 = $("#registerFrm #pword2").val();
				fn = $("#registerFrm #fname").val();
				sn = $("#registerFrm #sname").val();

				
				flag = true;
				
				if(un.length == 0) {
					$("#username_err").css("display", "inline");
					flag = false;
				} else {
					$("#username_err").css("display", "none");
					flag = flag + true;
				}
				
				if(pw.length == 0) {
					$("#password_err").css("display", "inline");
					flag = false;
				} else {
					$("#password_err").css("display", "none");
					flag = flag + true;
				}
				
				if(pw2.length == 0) {
					$("#password2_err").css("display", "inline");
					flag = false;
				} else {
					$("#password2_err").css("display", "none");
					if(pw == pw2) {
						$("#notmatching").css("display", "none");
						flag = flag + true;
					} else {
						$("#notmatching").css("display", "inline");
						flag = false;
					}
				}
				
				if(fn.length == 0) {
					$("#name_err").css("display", "inline");
					flag = false;
				} else {
					$("#name_err").css("display", "none");
					flag = flag + true;
				}
				
				if(sn.length == 0) {
					$("#surname_err").css("display", "inline");
					flag = false;
				} else {
					$("#surname_err").css("display", "none");
					flag = flag + true;
				}
				
				return flag;
			}
			
			function moveToSecure(uid, un) {
				$.ajax({
					method: "POST",
					url: "action/initialise.php",
					data: {user: uid, un: un, refer: "index.php"},
					cache: false,
					success: function (result) {
						alert("Successful login. \nMoving to secure page.");
						window.location.replace("main.php");
					}
				});
			}
			
		});
	</script>
</head>
<body>
<div id="container">
	<div id="header" class="opaque">
		<h1>Tic-Tac-Toe</h1>
	</div>
	<div id="menu" class="opaque">
		<ul>
			<li><a href="#">Home</a></li>
		</ul>
	</div>
	<div id="content" class="nonOpaque">
		<p>
			Please select an option from the list below.
		</p>
		<table id="options">
		<tr>
			<td><button id="login">Login</button></td><td><button id="register">Register</button></td>
		</tr>
		</table>
		<div id="loginFrmDiv" class="hideDiv">
			<!--form id="loginFrm"-->
				<table id="loginFrm">
				<tr>
					<td><label for="uname" autofocus>Username:</label></td>
					<td><input type="text" id="uname" name="uname" tabindex="1" /></td>
				</tr>
				<tr>
					<td><label for="pword">Password:</label></td>
					<td><input type="text" id="pword" name="pword" tabindex="2" /></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td><button tabindex="3" id="loginSub">Submit</button></td>
					<td><input type="reset" tabindex="4" /></td>
				</tr>
				</table>
			<!--/form-->
			<br />
		</div>
		<div id="registerFrmDiv" class="hideDiv">
			<!--form id="registerFrm"-->
				<table id="registerFrm">
				<tr>
					<td><label for="uname" autofocus>Username:</label></td>
					<td><input type="text" id="uname" name="uname" tabindex="1" /><span id="username_err" style="display:none;color:red;font-weight:bold;">This field cannot be blank.</span></td>
				</tr>
				<tr>
					<td><label for="pword">Password:</label></td>
					<td><input type="text" id="pword" name="pword" tabindex="2" /><span id="password_err" style="display:none;color:red;font-weight:bold;">This field cannot be blank.</span></td>
				</tr>
				<tr>
					<td><label for="pword2" autofocus>Confirm password:</label></td>
					<td><input type="text" id="pword2" name="pword2" tabindex="3" /><span id="password2_err" style="display:none;color:red;font-weight:bold;">This field cannot be blank.</span><span id="notmatching" style="display:none;color:red;font-weight:bold;">Password not confirmed - doesn't match above</span></td>
				</tr>
				<tr>
					<td><label for="fname" autofocus>Name:</label></td>
					<td><input type="text" id="fname" name="fname" tabindex="4" /><span id="name_err" style="display:none;color:red;font-weight:bold;">This field cannot be blank.</span></td>
				</tr>
				<tr>
					<td><label for="sname" autofocus>Surname:</label></td>
					<td><input type="text" id="sname" name="uname" tabindex="5" /><span id="surname_err" style="display:none;color:red;font-weight:bold;">This field cannot be blank.</span></td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td><input type="submit" tabindex="7" id="registerSub" /></td>
					<td><input type="reset" tabindex="8" /></td>
				</tr>
				</table>
			<!--/form-->
			<br />
		</div>
	</div>
</div>
</body>
</html>