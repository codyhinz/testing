<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With');

require '../../config/database.php';
require '../../models/author.php';

$database = new Database();
$db = $database->connect();

$auth = new Author($db);

$data = json_decode(file_get_contents("php://input"));

$auth->author = $data->author;
$auth->id = $data->id;

if(!empty($auth->author)) {
    $auth->update();
    echo json_encode(
        array('message' => 'Author updated')
    );
} else {
    echo json_encode(
        array('message' => 'Author was not updated')
    );
}