<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require '../../config/Database.php';
require '../../models/Quote.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate Quote object
$quote = new Quote($db);

//Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$quote->id = $data->id;

if(!empty($quote->id)) {
    //delete quote
    $quote->delete();
    echo json_encode(
        array('message' => 'Quote deleted')
    );
} else {
    echo json_encode(
        array('message' => 'Quote was not deleted')
    );
}