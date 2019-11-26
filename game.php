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
					if(gameOver) {						//Check if game is over.
						alert("Game is over!");
					}
					else if(!currentPlayerMove)
					{
						alert("It is not your turn!");	//Check if current player can move.
					}
					else if(!gameStarted){
						alert("Waiting for opponent!");
					}
					else {
						var coords = event.target.id.split("_"); //Get x and y coords from *this* square.
						var x = parseInt(coords[0]);
						var y = parseInt(coords[1]);
						$.ajax({ 						//Check if chosen square is available.
							method: "POST",
							url: "action/checksquare.php",
							data: {x: x, y: y},
							cache: false,
							success: function (response){
								if(response == 0) {
									$.ajax({ 			//Try to take square and win checked if successful.
										method: "POST",
										url: "action/takesquare.php",
										data: {x: x, y: y},
										cache: false,
										success: function (result){
											if(result >= 0 && result <= 3)  //Current player move false if successful.
											{
												currentPlayerMove = false;
												updateBoard();
											}
											else
											{
												alert(result);
											}
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
			
			function addGrid() {			//Create game board.
				for(y=0;y<3;y++){
					for(x=0;x<3;x++) {
						var $newDiv = $("<div id='" + x + "_" + y + "' class='gridbox'></div>");
						$("#grid").append($newDiv);
					}
				}
			}
			
			function gameStatus()			//Check status of game and note if game has started or is over.
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
							$("#status").html("Status: Waiting for opponent!");
						}
						else if(result == 0) {
							gameStarted = true;
							if(currentPlayerMove) {
								$("#status").html("Status: Play!");
							}
							else {
								$("#status").html("Status: Waiting for opponents move!");
							}
						}
						else if(result == 1) {
							gameOver = true;
							$("#status").html("Status: Game Over!");
						}	
						else if(result == 2) {
							gameOver = true;
							$("#status").html("Status: Game Over!");
						}
						else if(result == 3) {
							gameOver = true;
							$("#status").html("Status: Over!");
						}
						else if(result == "ERROR-NOGAME") {
							//Do nothing
						}
						else {
							alert(result);
						}
					}
				});
			}
			
			updateBoard();
			var gameStateTimer = window.setInterval(updateBoard, 1000);		//Timer to update game board every second.
			
			function updateBoard()					//Update game board.
			{
				$.ajax({							//Get current board.
					method: "POST",
					url: "action/getboard.php",
					cache: false,
					success: function (result) {
						if(result == "ERROR-DB") {
							alert(result);
						}
						else if (result == "ERROR-NOMOVES") {
							//Do Nothing.
						}
						else {
							var moves = [];
							var movesSplit = result.split("\n");				//Split result into moves.
							
							$.each(movesSplit, function(index, value) {			//Iterate through split and push back pid, x and y values with key to moves array.
								var move = value.split(",");
								moves.push({pid: move[0], x: move[1], y: move[2]});
							});
							
							if(moves.length > 0) {								//If at least one move, check who moved last and update current player move.
								if (moves[moves.length - 1]["pid"] == uid) {
									currentPlayerMove = false;
								}
								else {
									currentPlayerMove = true;
								}
							}
							
							for (i=0;i<moves.length;i++)						//Update game board for all moves made.
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
					}
				});
				gameStatus();			//Check game status.
				if(!gameOver)			//If game is over, check if it has been won.
				{
					checkWin();
				}
			}
			
			function checkWin()			//Check if game has been won or drawn.
			{
				$.ajax({ 
					method: "POST",
					url: "action/checkwin.php",
					cache: false,
					success: function (result){
						if(result >= 0 && result <= 3)
						{
							if(result == 3)
							{
								gameOver = true;
								if(confirm("Draw! Back to Menu? (y/n)")) {			//Back to main menu prompt if game is over.
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
			
			$("#delete").click(function() {							//Exit game and delete it.
				if(confirm("Warning, game will be deleted!\nContinue anyway? (y/n)")) {
					$.ajax({
						method: "POST",
						url: "action/deletegame.php",
						cache: false,
						success: function (result) {
							if(result == "ERROR-GAMESTARTED") {
								alert("Unable to delete game!\n(Not your game/Already started)");
							}
							else if(result == "ERROR-DB") {
								alert(result);
							}
							else {
								alert("Game Deleted!");
								window.location.replace("main.php");
							}
						}
					});
				}
			});

		}); 
	</script>
</head>
<body>
<div id="container">
	<div id="header" class="opaque">
		<h1>Tic-Tac-Toe</h1>
		<h3 id="status">Status: </h3>
	</div>
	<div id="menu" class="opaque">
		<ul>
			<li><a href="main.php">Home</a></li>
			<li><a href="logout.php">Logout</a></li>
			<li><button id="delete">Delete Game</button></li>
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
