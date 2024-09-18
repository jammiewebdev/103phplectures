<?php 

//String
$name = 'Ryan Azur';
$name2 = "Alden Rye Azur";

/* Display the value and the type of $name using var_dump
value = 9 characters including space -> Ryan Azur
type = string because enclosed in ''
*/
var_dump($name);
echo '<br>'; /* line break */
var_dump($name2);
echo '<br>'; /* line break */
echo '<br>'; /* line break */

// Display the type of the $name using gettype
echo gettype($name);
echo '<h1>' . gettype($name) . '</h1>';
echo '<br>'; /* line break */
echo '<br>'; /* line break */

//Echo both names
echo $name; /* line break */
echo '<br>'; /* line break */
echo $name2; /* line break */
echo '<br>'; /* line break */
echo '<br>'; /* line break */

//Integer
$age = 19;
echo $age;
echo '<br>';
var_dump ($age);
echo '<br>';
echo '<br>';

//Float
$rating = 4.5;
var_dump($rating);
echo '<br>';
echo '<br>';
echo '<br>';

//Boolean
$is_loaded = true;
var_dump($is_loaded);
echo '<br>';
echo '<br>';

//Array
$friends = array('Jun', 'Neil', 'Christian' );
var_dump($friends);
echo '<br>';
echo '<br>';
echo gettype($friends);
echo '<br>';
echo '<br>';

//Objects - are created from a class
$person = new stdClass();
var_dump($person);
echo '<br>';
echo gettype($person);
echo '<br>';
echo '<br>';

//Null - there's just no value
$car = null;
var_dump($car);
echo '<br>';
echo '<br>';

//Resource
$file = fopen('sample.txt', 'r');
echo gettype($file); //resource
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>hello</h1>
</body>
</html>