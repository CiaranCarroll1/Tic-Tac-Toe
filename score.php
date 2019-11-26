<?php
	session_start();
	
	if(!isset($_SESSION["uid"]) || !isset($_SESSION["username"])) {
		header("location:index.php?err=1");
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
		var uid = '<?php echo $_SESSION["uid"]; ?>';
		var un = '<?php echo $_SESSION["username"]; ?>';
		
		$(document).ready( function() {
			
			$.ajax({
				method: "POST",
				url: "action/getleaderboard.php",
				cache: false,
				success: function (result) {
					if(result != "ERROR-NOGAMES")
					{
						updateScores(result);
					}
				}
			});
			
			function updateScores(result)
			{
				var players = [];
				var win;
				var loss;
				var draw;
				var games = [];
				var gamesSplit = result.split("\n");
				
				$.each(gamesSplit, function(index, value) {
					var game = value.split(",");
					games.push({p1: game[1], p2: game[2], res: game[3]});
				});
					
				win = 0;
				loss = 0;
				draw = 0;
				
				for(y=0;y<games.length;y++)
				{
					var p1 = games[y]["p1"];
					var p2 = games[y]["p2"];
					var result = games[y]["res"];
					
					
					if((p1 == un && result == 1) || (p2 == un && result ==2))
					{
						win = win + 1;
					}
					
					if((p1 == un && result == 2) || (p2 == un && result ==1))
					{
						loss = loss + 1;
					}
					
					if((p1 == un || p2 == un) && result == 3)
					{
						draw = draw + 1;
					}
				}
				
				var $newRow = $("<tr><td><div>" + win + "</div></td><td><div>" + loss + "</div></td><td><div>" + draw + "</div></td></tr>");
				$("#results > tbody").append($newRow);
				
			
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
			<li><a href="main.php">Home</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<div class="content">
		<table id="results">
			<h2>My results</h2>
		<thead>
			<tr>
				<th><div>Wins:</div></th>
				<th><div>Losses:</div></th>
				<th><div>Draws:</div></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		</table>
    </div>
</div>
</body>
</html>