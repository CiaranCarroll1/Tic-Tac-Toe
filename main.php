<?php
	session_start();
	
	if(!isset($_SESSION["uid"]) || !isset($_SESSION["username"])) {
		header("location:index.php?err=1");
	}
	
	if(isset($_SESSION["gid"])){
		unset($_SESSION["gid"]);
	}
?>
<!DOCTYPE html>
<html charset="utf-8" lang="en-ie">
<head>
	<title>Tic-Tac-Toe</title>
	<link rel="stylesheet" href="styles.css" />
	<link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet" media="all" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
	var un = '<?php echo $_SESSION["username"]; ?>';
	
		$(document).ready( function() {
			
			$.ajaxSetup({ cache: false });
			
			$("#myscore").click( function() {
				window.location.replace("score.php");
			}); 
			
			$("#leaderboard").click( function() {
				window.location.replace("leaderboard.php");
			});
			
			$("#newgame").click(function() {
				
				$.ajax({
					method: "POST",
					url: "action/newgame.php",
					cache: false,
					success: function (result) {
						alert("Game created!");
						window.location.replace("game.php");
					}
				});
			});
			
			$(document).on("click", ".joinbutton", function(){
				if($(this).hasClass("joinbutton")) {
					var gid = event.target.id;
					
					$.ajax({
						method: "POST",
						url: "action/joingame.php",
						data: {gid: gid},
						cache: false,
						success: function (result) {
							alert("Game joined!");
							window.location.replace("game.php");
						}
					});
				}
			}); 
			
			$(document).on("click", ".rejoinbutton", function(){
				if($(this).hasClass("rejoinbutton")) {
					var gid = event.target.id;
					
					$.ajax({
						method: "POST",
						url: "action/rejoingame.php",
						data: {gid: gid},
						cache: false,
						success: function (result) {
							window.location.replace("game.php");
						}
					});
				}
			}); 
			
			updateOpenGames();
			var openGamesTimer = window.setInterval(updateOpenGames, 1000);
			function updateOpenGames()
			{
				$.ajax({
					method: "POST",
					url: "action/showopengames.php",
					cache: false,
					success: function (result) {
						if(result != "ERROR-NOGAMES")
						{
							var games = [];
							var gamesSplit = result.split("\n");
							
							$.each(gamesSplit, function(index, value) {
								var game = value.split(",");
								games.push({gid: game[0], user: game[1], date: game[2]});
							});
							
							if(games.length > 0)
							{
								$("#opengames > tbody").empty();
								
								for(i=0; i<games.length;i++)
								{
									if(games[i]["user"] != un)
									{
			
										var $newRow = $("<tr><td><div>" + games[i]["gid"] + "</div></td><td><div>" + games[i]["user"] + "</div></td><td><div>" + games[i]["date"] + "</div></td><td><div><button id='" + games[i]["gid"] + "' class='joinbutton'>Join</button></div></td></tr>");
										$("#opengames > tbody").append($newRow);
									}
									else
									{
										var $newRow = $("<tr><td><div>" + games[i]["gid"] + "</div></td><td><div>" + games[i]["user"] + "</div></td><td><div>" + games[i]["date"] + "</div></td><td><div><button id='" + games[i]["gid"] + "' class='rejoinbutton'>Re-join</button></div></td></tr>");
										$("#opengames > tbody").append($newRow);
									}
								}
							}
						}
					}
				});
			}
			
		});
	</script>
</head>
<body>
<div id="container">
	<div id="header" class="opaque">
		<h1>Tic-Tac-Toe - Welcome <?php echo $_SESSION["username"];?></h1>
	</div>
	<div id="menu" class="opaque">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<div id="content" class="nonOpaque">
		<table id="services">
		<h3>Welcome - Home</h3>
			<tr>
				<td><button id="myscore">My Score</button></td>
				<td><button id="leaderboard">Leaderboard</button></td>
				<td><button id="newgame">New Game</button></td>
			</tr>
		</table>
		<table id="opengames">
		<thead>
		<h3>Open Games</h3>
		<tr>
			<th><div>Game id:</div></th>
			<th><div>User:</div></th>
			<th><div>Started:</div></th>
			<th><div>Join Game:</div></th>
		</tr>
		<thead>
		<tbody>
		</tbody>
	</div>
</div>
</body>
</html>