<?php

 //Headers
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 require '../../config/Database.php';
 require '../../models/Quote.php';

 //Instantiate DB & Connect
 $database = new Database();
 $db = $database->connect();

 //Instantiate Quote object
 $quote = new Quote($db);

 //Get ID (can be from the url)
 $quote->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

 //Get quote

$quote->read_single();

//Create array
$quote_arr = array(
    'id' => $quote->id,
    'quote' => $quote->quote,
    'author' => $quote->author,
    'category' => $quote->category
);

if(!empty($quote_arr['quote'])) {
    echo json_encode($quote_arr);
} else {
    echo json_encode(
        array("message" => "No quote found with the specified id")
    );
}






