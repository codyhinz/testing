<?php

 //Headers
 header('Access-Control-Allow-Origin: *');
 header('Content-Type: application/json');

 require '../../config/Database.php';
 require '../../models/Category.php';

 //Instantiate DB & Connect
 $database = new Database();
 $db = $database->connect();

 //Instantiate Category object
 $cat = new Category($db);

 //Get ID (can be from the url)
 $cat->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

 //Get category
$cat->read_single();

//Create array
$cat_arr = array(
    'id' => $cat->id,
    'category' => $cat->category
);

if(!empty($cat_arr['category'])) {
    echo json_encode($cat_arr);
} else {
    echo json_encode(
        array("message" => "No category found with the specified id")
    );
}






