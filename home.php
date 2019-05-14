<?php

include 'class.php';
session_start();

//CREATING AN ARRAY,ASSOCIATIVE ARRAY AND AN OBJECT.

//Normal array
$Array = array('Sarah', 'Kenneth', 'Johan');

//Associative Array
$AssocArray = array(
    "Serie" => array("Familie", "Thuis"),
    "Cookies" => array("Kinder Bueno", "Wafels")
);

//Object
class littledog
{
    var $weight = 6;
    var $gender = 'female';
    var $color = 'white-brown';
};
$newdog = new littledog;


//Write a for-loop that adds an item to all of the above.
//loops through the given numbers of items.


for ($i = 0; $i <= 3; $i++) {
    if ($i === 1) {
        array_push($Array, 'Frieda');
    } else if ($i === 2) {
        array_push($AssocArray["Serie"], 'Game of thrones', 'Prisonbreak');
        array_push($AssocArray["Cookies"], 'Madeleine', 'Bounty');
        $AssocArr["Frisdrank"] = array('Coca Cola', 'Ice tea', 'Fanta');
    } else if ($i === 3) {
        $newdog->ras = 'Jack russel';
    }
}

//STORE ARRAYS IN A SESSION

$_SESSION['Normal array'] = $Array;
$_SESSION['Associative array'] = $AssocArray;
$_SESSION['Object'] = $newdog;

//Write an if-statement that has a 20% chance to edit a random item of all of the above.
//selects a number between 1 and 100, if below 20 we change something.

if (rand(1, 100 <= 20)) {
    switch (rand(1, 3)) {
        case 1:
            $Array[array_rand($Array)] = "replaced";
            $_SESSION['Normal array'] = $Array;
            break;
        case 2:
            $random = array_rand($AssocArray);
            $AssocArray[$random][array_rand($AssocArray[$random])] = "replaced";
            $_SESSION['Associative array'] = $AssocArray;
            break;
        case 3:
            $arraydog = (array)$newdog;
            $dog = array_rand($arraydog);
            $arraydog[$dog] = "replaced";
            $newdog = new Mopshond;
            foreach ($arraydog as $key => $values) {
                $newdog->$key = $values;
            }
            $_SESSION['Object'] = $newdog;
            break;
    }
}
?>
