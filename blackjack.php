<?php

// Make a blackjack class with de functions hit, stand and surrender.
class blackjack {
    public $totalScore = 0;
    public $totalLose = 0;
    public $Ties = 0;
    public $card = [];
    public $endturn = true;
    public $score = 0;
    public $hand = [];
    public $surrender = false;
    public $restart = '';

// Make a hit function, add a card between 1-11.
    public function hit(){
        array_push($this->hand,mt_rand(1,11));
        $this->score = array_sum($this->hand);
    }
// Should end your turn, and start the dealers turn.
// Total point is saved.
    public function stand(){
        $_SESSION['score'] = $this ->score;
        $this ->endturn = false;
    }
// Make you surrender the game -> Dealer wins.
    public function surrender(){
        $_SESSION['message'] = 'Dealer wins';
        $this ->surrender = true;
    }
}

?>