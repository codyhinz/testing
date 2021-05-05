<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require '../../config/Database.php';
require '../../models/Author.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate Author object
$auth = new Author($db);

//Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$auth->author = $data->author;

if(!empty($auth->author)) {
    //create author
    $auth->create();
    echo json_encode(
        array('message' => 'Author created')
    );
} else {
    echo json_encode(
        array('message' => 'Author was not created')
    );
}