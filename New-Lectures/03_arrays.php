<?php
// Arrays

// If you need to store multiple values, you can use arrays. Arrays hold "elements."

// Simple array of numbers
$numbers = [1,2,3,4,5];

// Simple array of strings
$colors = ['red', 'green', 'blue'];

// print_r($numbers);
// print_r($colors);

//use echo to access just 1 element in the array
// echo $colors[1]; 

//to add
// echo $numbers[1] + $numbers[3];

//Associative arrays - allows us to use named keys to identify values.
// $colors = [
//     1 => "red",
//     2 => 'green',
//     3 => "blue",
// ];
// echo $colors[1]

//Strings as keys - used for APIs
// $hex = [
//     "red" => "#f00",
//     "green" => "#0f0",
//     "blue" => "#00f",
// ];
// echo $hex['red'];

//Single Person
// $person = [
//     "first_name" => "Ryan",
//     "last_name" => "Azur",
//     "email" => "razur@gmial.com",
// ];
// echo $person["first_name"];

//Multidimensional array - often used to store data in a table format
$teamazur = [
    [
        "first_name" => "Ryan",
        "last_name" => "Azur",
        "email" => "razur@gmial.com",
    ],
    [
        "first_name" => "Pauline",
        "last_name" => "Azur",
        "email" => "pazur@gmial.com",
    ],
    [
        "first_name" => "Alden",
        "last_name" => "Azur",
        "email" => "aazur@gmial.com",
    ],
    [
        "first_name" => "Catrice",
        "last_name" => "Azur",
        "email" => "cazur@gmial.com",
    ],
]; 
// var_dump($teamazur)
// echo $teamazur[0]["first_name"]

//ENCODE TO JSON
// var_dump(json_encode($teamazur));

//DECODE TO JSON - use SINGLE QUOTE ONLY
$jsonobj = '{
    "first_name":"Ryan",
    "last_name":"Azur",
    "email":"razur@gmial.com"
}';
var_dump(json_decode($jsonobj));
?>