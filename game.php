<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		
		<style>
			img {
				width: 100px;
				height: 100px;
			}
			td {
				width: 100px;
				height: 100px;
			}
			 H1 {
					color: #106f9c;
					text-align: center;
			}			
			H2 {
				color: #0a45a3;	
				text-align: center;
			}
			form {
				color: #0a45a3;
				font-weight: bold;
				text-align: center;
			}
			body {
			background-color: #e8f4f8;	
			}
		</style>
	</head>
	<body>
	<?php
		if (isset($_POST["Total"]))     // Game Over page
		{
			echo $_SESSION["playerx"]; ?>'s Points:
			<br>
				<?php
                // echo as many 'x' pictures as the x points
				for ($pointsNum = 0; $pointsNum < $_SESSION["scorex"]; $pointsNum++)
				{
					echo '<img src="x.png" alt="x">';
				}
				?>
			<br>
			<?php echo $_SESSION["playero"]; ?>'s Points: 
			<br> 
				<?php
                // echo as many 'y' pictures as the y points
				for ($pointsNum = 0; $pointsNum < $_SESSION["scoreo"]; $pointsNum++)
				{
					echo '<img src="o.png" alt="o">';
				}
				?>
			<br>
			<form action="game.php" method="POST">
				<input type="submit" name="NewGame" value="Another game">
			</form>
			<?php
            // Get rid of all the session's variables
			unset($_SESSION["playerx"]);
			unset($_SESSION["playero"]);
			unset($_SESSION["scorex"]);
			unset($_SESSION["scoreo"]);
			unset($_SESSION["turn"]);
			unset($_SESSION["box1"]);
			unset($_SESSION["box2"]);
			unset($_SESSION["box3"]);
			unset($_SESSION["box4"]);
			unset($_SESSION["box5"]);
			unset($_SESSION["box6"]);
			unset($_SESSION["box7"]);
			unset($_SESSION["box8"]);
			unset($_SESSION["box9"]);
		}
		else if (isset($_SESSION["scorex"]) || isset($_POST["start"]))  // Game continues
		{
			if (isset($_POST["start"]))     // The game have just been started
			{
                // Set all the session's variables
				$_SESSION["playerx"] = $_POST["player1"];
				$_SESSION["playero"] = $_POST["player2"];
				$_SESSION["scorex"] = 0;
				$_SESSION["scoreo"] = 0;
				$_SESSION["box1"] = 'n';
				$_SESSION["box2"] = 'n';
				$_SESSION["box3"] = 'n';
				$_SESSION["box4"] = 'n';
				$_SESSION["box5"] = 'n';
				$_SESSION["box6"] = 'n';
				$_SESSION["box7"] = 'n';
				$_SESSION["box8"] = 'n';
				$_SESSION["box9"] = 'n';
				$_SESSION["turn"] = 'x';
				$status = 3;
			}
			else if (isset($_POST["box"]))  // The game is already running, and a player just made a move
			{
                // mark The move
				$_SESSION["box" . $_POST["box"]] = $_SESSION["turn"];
                unset($_POST["box"]);
                // check status, while the value of $status means - 0: temporary status, 1: win, 2: tie, 3: the game continues
                $status = 0;
				// check if box5 is marked, and if it is, check diaginals
				if (($_SESSION["box5"] != 'n') &&
						(
						(($_SESSION["box1"] == $_SESSION["box5"]) && ($_SESSION["box1"] == $_SESSION["box9"]))
						|| (($_SESSION["box5"] == $_SESSION["box3"]) && ($_SESSION["box5"] == $_SESSION["box7"]))
						)
					)
				{
					$status = 1;    // someone won
				}
				if ($status == 0) // if there wasn't a win by diagonals, check rows and columns 
				{
				// loop i = 1 to 3
					for ($colRowNum = 0; $colRowNum < 3; $colRowNum++)
					{
						// check if box_(4*i + 1) is marked
						// if it is, check row and column
						if (($_SESSION["box" . (4 * $colRowNum + 1)] != 'n') &&
							(
							(($_SESSION["box" . (3* $colRowNum + 1)] == $_SESSION["box" . (3 * $colRowNum + 2)]) && ($_SESSION["box" . (3 * $colRowNum + 1)] == $_SESSION["box" . (3 * $colRowNum + 3)]))
							|| (($_SESSION["box" . ($colRowNum + 1)] == $_SESSION["box" . ($colRowNum + 4)]) && ($_SESSION["box" . ($colRowNum + 1)] == $_SESSION["box" . ($colRowNum + 7)]))
							)
						)
						{
							$status = 1;    // someone won
						}
					}
				}
				if ($status == 0)   // if nobody won, check whether there's a tie
				{
					$status = 2;    // assume there's a tie until you'll find a free cell to disprove it
					for ($cell = 1; $cell <= 9; $cell++)
					{
						if ($_SESSION["box" . $cell] == 'n')
						{
							$status = 3;    // a free cell disproved it; the game continues.
						}
					}
                    if ($status == 3)
                    {
                        // flip the who's-turn-is-it variable
                        if ($_SESSION["turn"] == 'x')
				        {
					        $_SESSION["turn"] = 'o';
				        }
				        else
				        {
					        $_SESSION["turn"] = 'x';
				        }
                    }
				}
			}
            else    // the game is already running, but no player was playing.
            {
                $status = 3; // the game continues
            }
			if ($status == 3)	// the game continues
			{
				?>
				<H1> Tic Tac Toe- ready? </H1>
				<H2> <?php echo $_SESSION["player" . $_SESSION["turn"]]; ?>'s Turn <H2>
				<form action="game.php" method="POST">
					<table border="5">
						<?php
                            // create board
							for ($row = 0; $row < 3; ++$row)
							{
								echo "<tr>";
								for ($col = 1; $col <= 3; ++$col)
								{
									echo "<td>";
									$box = $_SESSION["box" . (($row * 3) + $col)];
									if ($box=='n')
									{
										echo '<input type="radio" name="box" value="' . (($row * 3) + $col) . '" onchange="document.getElementById(\'makeMove\').disabled = false;">';
									}
									else
									{
										echo '<img src="' . $box . '.png">';
									}
									echo "</td>";
								}
								echo "</tr>";
							}
						?>
					</table>
					<input type="submit" value="Play" disabled="true" id="makeMove">
				</form>
				<?php
			}
			else				// the round ends
			{
				?>
				<H1>
				<?php
				if ($status == 2) // a tie
				{
					// tie
					echo "No one won! It's a tie";
				}
				else // someone won, it's the one who just played
				{
					echo $_SESSION["player" . $_SESSION["turn"]] . " Won!";
					$_SESSION["score" . $_SESSION["turn"]]++;
				}
                // reset all the variables that should be reset
				$_SESSION["turn"] = 'x';
				$_SESSION["box1"] = 'n';
				$_SESSION["box2"] = 'n';
				$_SESSION["box3"] = 'n';
				$_SESSION["box4"] = 'n';
				$_SESSION["box5"] = 'n';
				$_SESSION["box6"] = 'n';
				$_SESSION["box7"] = 'n';
				$_SESSION["box8"] = 'n';
				$_SESSION["box9"] = 'n';
				?>
				</H1>
				<form action="game.php" method="POST">
					<input type="submit" name="NewRound" value="Another round">
					<input type="submit" name="Total" value="Total and Exit">
				</form>
				<?php
			}
		}
		else // opening page
		{
			?>
			<H1>
				Welcome To The Game
			</H1>
			<H2> Choose Your Players Names </H2>
			<form action="game.php" method="POST">
				First Player (X): <input type="text" name="player1" id="player1" onchange="checkNames();">
				<br>
				Second Player (O): <input type="text" name="player2" id="player2" onchange="checkNames();"> 
				<br>
				<input type="submit" name="start" value="Let's Play" id="startGame" disabled="true">
			</form>
			<script>
				function checkNames() {
					if (document.getElementById("player1").value != "" && document.getElementById("player2").value != "") {
						document.getElementById("startGame").disabled = false;
					} 
					else {
						document.getElementById("startGame").disabled = true;
					}
				}
			</script>
			<?php
		}
	?>
	</body>
</html>