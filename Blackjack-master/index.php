<?php
require_once("class.Game.php");

// Establish defaults
$gameOver = 0;
$game = new Game();		// Create a new deck and start a new game

/**clear all session variables if user plays again**/
if (isset($_GET['again'])) {

}
session_start();
if (!isset($_GET['hit']) && !isset($_GET['stand'])) {
    /**initial deal**/
    $userHand[0] = $game->dealCard();
    $dealerHand[0] = $game->dealCard();
    $userHand[1] = $game->dealCard();
    $dealerHand[1] = $game->dealCard();
    $_SESSION['userHand'] = $userHand;
    $_SESSION['dealerHand'] = $dealerHand;
	$_SESSION['dHandValue'] = $game->getHandValue($_SESSION['dealerHand']);
} else if (isset($_GET['hit'])) {
    $_SESSION['userHand'][sizeof($_SESSION['userHand'])] = $game->dealCard();
    $_SESSION['userValue'] = $game->getHandValue($_SESSION['userHand']);
	$_SESSION['dHandValue'] = $game->getHandValue($_SESSION['dealerHand']);
	$_SESSION['uHandValue'] = $game->getHandValue($_SESSION['userHand']);
	// Auto-stand if at 21
    if ($_SESSION['userValue'] == 21)
		header("Location: index.php?stand=stand");
	// Check if, by hitting, the game has ended
	$gameOver = $game->winCheck($_SESSION['userValue'], $_SESSION['dHandValue'], 0);
} else if (isset($_GET['stand'])) {
    while ($_SESSION['dHandValue'] < 17) {
        $_SESSION['dealerHand'][sizeof($_SESSION['dealerHand'])] = $game->dealCard();
        $_SESSION['dHandValue'] = $game->getHandValue($_SESSION['dealerHand']);
		$_SESSION['uHandValue'] = $game->getHandValue($_SESSION['userHand']);		
    }
	$gameOver = $game->winCheck($_SESSION['uHandValue'], $_SESSION['dHandValue'], 1);
}

?>

<html>
<head>
<style type="text/css">
	body {
		margin:0px;
        color: white;
        font-family: arial;
	}
    input[type="submit"]{
        background-color: green;
        border-radius: 5px;
        padding: 5px;
        color: white;
        margin-top: 10px;
    }
    .container{
        width: 100%;
        background-image: url("../cards/bg.jpg");
        height: 100vh;
    }
    h2{
        top: 30px;
        position: relative;
        text-align: center;
        
    }
    .box{
        background-color: black;
        padding: 5px;
        width: 500px;
        margin:auto;
        height: 460px;
        text-align: center;
        margin-top: 100px;
    }
    .win, .lose{
        height: 20px;
        background-color: blue;
        width: 200px;
        text-align: center;
        padding: 10px; 
        margin-left: 570px;
        margin-top: 70px;
        position: absolute;
        font-weight: bolder;
        font-size: 20px;
    }
    .lose{
        background-color: red;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Blackjack</h2>
    <div class='box'>
        <h3>Your hand is:</h3>
    <?php 
	// Show cards
	for ($i = 0; $i < sizeof($_SESSION['userHand']); $i++) {
        echo $game->translateCard($_SESSION['userHand'][$i]);
    }
    

    echo "<div style='text-decoration:underline; font-weight:bold;'><br /><br />Your opponents visible cards: </div><br />";
	if ($gameOver == 0)
	{
		for ($j = 1; $j < sizeof($_SESSION['dealerHand']); $j++) {

			echo $game->translateCard($_SESSION['dealerHand'][$j]) . "<br />";
		}
	}
	else
	{
		for ($j = 0; $j < sizeof($_SESSION['dealerHand']); $j++) {
			echo $game->translateCard($_SESSION['dealerHand'][$j])  ;
		}
	}
	
	echo "<br /><br />";
    /**game is not over; reload screen like normal**/
    if ($gameOver == 0){
        echo '<form style=\'text-align:center\' action=\'index.php\' method=\'get\'>
                      <input type=\'submit\' name=\'hit\' value=\'hit\'/><br />
                      <input type=\'submit\' name=\'stand\' value=\'stand\'/></form>';
    } /**Victory conditions are met; print final screen**/
    else{

      echo 'Your final score was: ' . $_SESSION['uHandValue'] . '<br /> Your opponents final score was: '.$_SESSION['dHandValue'].'
            <form style=\'text-align:center\' action=\'index.php\' method=\'get\'>
            <input type=\'submit\' name=\'again\' value=\'Play Again\'/></form>';
    } ?>
</div>
</div>
</body>
</html>


