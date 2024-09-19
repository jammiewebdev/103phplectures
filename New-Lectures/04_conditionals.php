<?php
// ------------------ Conditionals and Operators --------------

// -------------- Operators -----------------

// < Less Than
// > Greater Than
// <= Less Than or equal to
// >= Greater than or equal to
// == Equal to
// === Identical to
// != Not eual to
// !== Not identical to

//---------- If Statements ----------

// If statement Syntax
/* if (condition) {
    //code to be executed if condition is true
}
    */

// $age = 20; //You are old enough to vote!
// $age = 12; //Sorry, You are too young to vote!

// if ($age >= 18) {
//     echo "You are old enough to vote!";
// } else {
//     echo "Sorry, You are too young to vote!";
// }

//Dates
// $t = date("H");
// echo($t);
// Y - year
// F - month
// j - date
// H - hour

// $t = date("H");
// $t = 13;

// if ($t < 12){
//     echo "Good Morning!";
// } elseif ($t < 17){
//     echo "Good afternoon!";
// } else {
//     echo "Good evening!";
// }

$posts = [];

if(empty($posts)){
    echo 1234;
}

?>