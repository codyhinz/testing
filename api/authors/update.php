<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
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
$auth->id = $data->id;

if(!empty($auth->author)) {
    //update author
    $auth->update();
    echo json_encode(
        array('message' => 'Author updated')
    );
} else {
    echo json_encode(
        array('message' => 'Author was not updated')
    );
}