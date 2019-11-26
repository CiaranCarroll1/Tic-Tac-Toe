<?php
	session_start();
	
	if(!isset($_SESSION["uid"])){
		header("location:index.php?err=1");
	}
	if(!isset($_SESSION["pn"]) || !isset($_SESSION["gid"])){
		header("location:main.php?err=1");
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
		var gid = '<?php echo $_SESSION["gid"]; ?>';
		var playerNumber = '<?php echo $_SESSION["pn"]; ?>';
		var currentPlayerMove = true;
		var gameStarted = false;
		var gameOver = false;
		var wid;
		
		$(document).ready( function() {
			addGrid();
			
			$(".gridbox").click(function() {
				if($(this).hasClass("gridbox")) {
					if(gameOver) {					
						alert("Game has finished");
					}
					else if(!currentPlayerMove)
					{
						alert("It is not your turn");
					}
					else if(!gameStarted){
						alert("Waiting for opponent");
					}
					else {
						var coords = event.target.id.split("_");
						var x = parseInt(coords[0]);
						var y = parseInt(coords[1]);
						$.ajax({ 
							method: "POST",
							url: "action/checksquare.php",
							data: {x: x, y: y},
							cache: false,
							success: function (response){
								if(response == 0) {
									$.ajax({ 
										method: "POST",
										url: "action/takesquare.php",
										data: {x: x, y: y},
										cache: false,
										success: function (result){
											currentPlayerMove = false;
											updateBoard();
										}
									});
								}
								else if(response == 1) {
									alert("Square already taken");
								}
								else {
									alert(response);
								}
							}
						});
					}
				}
			}); 
			
			function addGrid() {
				for(y=0;y<3;y++){
					for(x=0;x<3;x++) {
						var $newDiv = $("<div id='" + x + "_" + y + "' class='gridbox'></div>");
						$("#grid").append($newDiv);
					}
				}
			}
			
			function gameStatus()
			{
				var val;
				$.ajax({
					method: "POST",
					url: "action/getgamestate.php",
					data: {gid: gid},
					cache: false,
					success: function (result) {
						if(result == -1) {
							gameStarted = false;
						}
						else if(result == 0) {
							gameStarted = true;
						}
						else if(result == 1) {
							gameOver = true;
						}	
						else if(result == 2) {
							gameOver = true;
						}
						else if(result == 3) {
							gameOver = true;
						}
					}
				});
			}
			
			updateBoard();
			var gameStateTimer = window.setInterval(updateBoard, 1000);
			
			function updateBoard()
			{
				$.ajax({
					method: "POST",
					url: "action/getboard.php",
					cache: false,
					success: function (result) {
						var moves = [];
						var movesSplit = result.split("\n");
						
						$.each(movesSplit, function(index, value) {
							var move = value.split(",");
							moves.push({pid: move[0], x: move[1], y: move[2]});
						});
						
						if(moves.length > 0) {
							if (moves[moves.length - 1]["pid"] == uid) {
								currentPlayerMove = false;
							}
							else {
								currentPlayerMove = true;
							}
						}
						
						for (i=0;i<moves.length;i++)
						{
							$('.gridbox').each(function() 
							{
								var coords = this.id.split("_");
								var x = parseInt(coords[0]);
								var y = parseInt(coords[1]);
								if (moves[i]['x'] == x && moves[i]['y'] == y)
								{
									if (moves[i]["pid"] == uid)
									{
										$(this).empty().append($("<img src='images/x.png'/>"));
									}
									else
									{
										$(this).empty().append($("<img src='images/o.png'/>"));
									}
								}
							});
						}
					}
				});
				gameStatus();
				if(!gameOver)
				{
					checkWin();
				}
			}
			
			function checkWin()
			{
				$.ajax({ 
					method: "POST",
					url: "action/checkwin.php",
					cache: false,
					success: function (result){
						if(result == 0)
						{
							gameOver = false;
						}
						
						if(result == 3)
						{
							gameOver = true;
							if(confirm("Draw! Back to Menu? (y/n)")) {
								window.location.replace("main.php");
							}
						}
						
						if(result == playerNumber)
						{
							gameOver = true;
							if(confirm("You win! Back to Menu? (y/n)")) {
								window.location.replace("main.php");
							}
						}
						else 
						{
							if((playerNumber == 1 && result == 2) || (playerNumber == 2 && result == 1))
							{
								gameOver = true;
								if(confirm("You lose! Back to Menu? (y/n)")) {
									window.location.replace("main.php");
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
		<h1>Tic-Tac-Toe</h1>
	</div>
	<div id="menu" class="opaque">
		<ul>
			<li><a href="main.php">Home</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<div class="content">
		<section class="game">
			<div id="grid"></div>
		</section>
    </div>
</div>
</body>
</html>
