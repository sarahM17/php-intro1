<?php
 require_once 'blackjack.php';
 session_start();

// Player start the session.
if (!isset($_SESSION['player'])) {
    session_start();
}

if(!isset($_SESSION['player'])) {
    //New blackjack variables.
    $player = new blackjack();
    $dealer = new blackjack();

    // Data will be serialized and stored in the variables.
    $_SESSION['player'] = serialize($player);
    $_SESSION['dealer'] = serialize($dealer);
}
// Data will be unserialized from the variables.
else {
    $dealer = unserialize($_SESSION['dealer']);
    $player = unserialize($_SESSION['player']);
}

// Restart the blackjack game.

if (isset($_POST['restart'])) {
    restart();
    header('location: game.php');
}
//reset the blackjack game.
if (isset($_POST['reset'])) {
    startSession();
    header('location: game.php');
}

// start the game with dealing 2 cards for the player.
if($player->score == null && $player->endturn == true){
    for($x = 0; $x < 2; $x++) {
        $player->hit();
    }
    if($player->score == 21){
        $_SESSION['message'] = "Blackjack! You win!";
        $player->totalScore += 1;
        $dealer->totalLose += 1;
        $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
        session_destroy();
    }
    $_SESSION['player'] = serialize($player);
}

// If the score of the first two cards hits 21! You automatically win the game.

if($player -> score == 21){
    $_SESSION["message"] = "You already won the game!";
    $player->totalScore += 1;
    $dealer->totalLose += 1;
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
    session_destroy();
}

// We insert the new data in the variables.

$_SESSION['player'] = serialize($player);


// When you click on the hit button.
// A card will be taken.

if(isset($_POST['hit']) && $player ->endturn == true) {
    $player ->hit();
    $_SESSION['player'] = serialize($player);
}


// When you click on the stand button your turn will end and the dealer will hit the turn.
if(isset($_POST['stand'])){
    $player->stand();
    $_SESSION['player'] = serialize($player);
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";

    do{
        $dealer->hit();
    } while($dealer->score < 15);
    // Dealer will eventually end his turn. New object data will be serialized and stored in session variable
    $dealer->stand();
    //gameDealer();
    $_SESSION['dealer'] = serialize($dealer);
}



// When you hits the surrender button, the dealer wins automatically and there comes a message.
if(isset($_POST['surrender'])){
    $player->surrender();
    for($x = 0; $x < 2; $x++){
        $dealer->hit();
    }
    while($dealer->score < 15) {
    // Dealer will eventually end his turn. New object data will be serialized and stored in session variable
    $dealer->stand();
    //gameDealer();
    $_SESSION['dealer'] = serialize($dealer);
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";

}
    session_destroy();
}



//THE GAME SCENARIOS!

// First check if the player is equal to dealer.
if($player->score == 21 && $dealer->score == 21)
{
    $_SESSION['message']= "Tie game";
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
    session_destroy();
}
elseif ($player->score <= 10) {
    $_SESSION['message'] = "It's your turn!";
}
elseif($player->score > 10 && $player->score <= 16) {
    $_SESSION['message'] = 'You are getting verry close now!';
}
elseif ($player->score > 16 && $player->score < 21) {
    $_SESSION['message'] = "I'd should stop.";
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
}
// Then check if the player score is greater than 21
elseif ($player ->score > 21 || $dealer == 21)
{
    $_SESSION['message']=  "You lost, the computer won";
    $dealer->totalScore += 1;
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
    session_destroy();
}
// Then check if the player score is less than 21
elseif ($dealer->score < 21 || $player == 21)
{
    $_SESSION['message']= "You won, the computer lost";
    $player->totalScore += 1;
    $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
    session_destroy();
}
// Check if both the player and dealer quit the game
elseif($player->turn == false && $dealer->turn == false) { // When both the player and dealer have ended their turn
    if($player->score <= $dealer->score){
        $_SESSION['message'] = "You lost! The computer wins the game.";
        $dealer->totalScore += 1;
        $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
        session_destroy();
    } else {
        $_SESSION['message'] = "You won the game!";
        $player->totalScore += 1;
        $_SESSION['restart'] = "<button type='submit' name='restart' class='btn btn-danger'>restart</button>";
        session_destroy();
    }
}

function gameDealer()
{
    $_SESSION['dealer']->hit();
    header('location: blackjack.php');
}

function restart(){
    $_SESSION['player']->score = 0;
    $_SESSION['dealer']->score = 0;
    $_SESSION['player']->endturn = true;
    $_SESSION['player']->surrender = false;

    $_SESSION['restart'] = '';
    array_push($_SESSION['dealer']->hand);
    for ($x = 0; $x < 2; $x++) {
        $_SESSION['player']->hit();
        array_push($_SESSION['player']->hand);
    };

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blackjack</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<div class="container">
    <h1>Blackjack</h1>

    <div class="row d-flex justify-content-center ">
        <div class="player col-md-6 mt-5">
            <h3 class="text-center">Player 1 current hand: <?php echo $player->score; ?></h3>
            <ul class="d-flex justify-content-center">
                <?php
                foreach ($player-> hand as $value) {
                    echo '<li class="li m-4 text-center list-unstyled">' . $value . '</li>';
                } ?>
            </ul>
        </div>
        <div class="computer col-md-6 mt-5">
            <h3 class="text-center">Dealers current hand: <?php echo $dealer->score ?></h3>
            <ul class="d-flex justify-content-center">
                <?php
                foreach ($dealer-> hand as $value) {
                    echo '<li class="li m-4 text-center list-unstyled">' . $value . '</li>';
                } ?>
            </ul>
        </div>
    </div>
    <div class="row d-flex justify-content-between m-5">
        <div class="playerScore d-flex align-items-center flex-column">
            <table class="table">
                <thead>
                <tr>
                    <th width="25%">Name</th>
                    <th width="12%">Total wins</th>
                    <th width="12%">Total lose</th>
                    <th width="12%">Ties</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Player 1</th>
                    <td width="12%"><?php echo $player->totalScore ?></td>
                    <td width="12%"><?php echo $player->totalLose ?></td>
                    <td width="12%"><?php echo $player->Ties ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="dealerScore d-flex align-items-center flex-column">
            <table class="table">
                <thead>
                <tr>
                    <th width="25%">Name</th>
                    <th width="12%">Total wins</th>
                    <th width="12%">Total lose</th>
                    <th width="12%">Ties</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Dealer</th>
                    <td width="12%"><?php echo $dealer->totalScore ?></td>
                    <td width="12%"><?php echo $dealer->totalLose ?></td>
                    <td width="12%"><?php echo $dealer->Ties ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row d-flex justify-content-center mb-5">
        <h2><?php echo $_SESSION['message']; ?></h2>
        <form action="" method="POST">

        </form>
    </div>
    <div class="row d-flex justify-content-center mt-5">
        <form action="" method="POST">
            <button type="submit" name="hit" value="hit" class="btn  btn-one">Start Game/hit</button>
            <button type="submit" name="stand" value="stand" class="btn btn-one">Stand</button>
            <button type="submit" name="surrender" value="surrender" class="btn btn-one">Surrender</button>
        </form>
    </div>
    <div class="row d-flex justify-content-center mt-5">
        <form action="" method="POST">
            <?php echo $_SESSION['restart']; ?>
        </form>
        <form action="" method="POST">
            <button type="submit" name="reset" class="btn btn-info">reset</button>
        </form>
    </div>
</div>
</body>
</html>




