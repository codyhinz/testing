<?php

 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 require '../../config/Database.php';
 require '../../models/Author.php';

 $database = new Database();
 $db = $database->connect();

 $auth = new Author($db);

 $auth->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$auth->read_single();

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






