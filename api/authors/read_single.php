<?php

 //Headers
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 require '../../config/Database.php';
 require '../../models/Author.php';

 //Instantiate DB & Connect
 $database = new Database();
 $db = $database->connect();

 //Instantiate Author object
 $auth = new Author($db);

 //Get ID (can be from the url)
 $auth->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

 //Get category
$auth->read_single();

//Create array
$auth_arr = array(
    'id' => $auth->id,
    'author' => $auth->author
);

if(!empty($auth_arr['author'])) {
    echo json_encode($auth_arr);
} else {
    echo json_encode(
        array("message" => "No author found with the specified id")
    );
}






