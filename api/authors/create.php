<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require '../../config/Database.php';
require '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$auth = new Author($db);

$data = json_decode(file_get_contents("php://input"));

$auth->author = $data->author;

if(!empty($auth->author)) {
    $auth->create();
    echo json_encode(
        array('message' => 'Author created')
    );
} else {
    echo json_encode(
        array('message' => 'Author was not created')
    );
}