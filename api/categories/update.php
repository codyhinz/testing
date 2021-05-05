<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require '../../config/Database.php';
require '../../models/Category.php';

//Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

//Instantiate Category object
$cat = new Category($db);

//Get raw data
$data = json_decode(file_get_contents("php://input"));

$cat->id = $data->id;
$cat->category = $data->category;

if(!empty($cat->id) &&
!empty($cat->category)) {
    //update category
    $cat->update();
    echo json_encode(
        array('message' => 'Category updated')
    );
} else {
    echo json_encode(
        array('message' => 'Category was not updated')
    );
}