<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require '../../config/database.php';
    require '../../models/author.php';

    $database = new Database();
    $db = $database->connect();

    $auth = new Author($db);

    $result = $auth->read();
    $num = $result->rowCount();

    if($num > 0) {
        $auth_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $auth_item = array(
                'id' => $id,
                'author' => $author
            );

            array_push($auth_arr, $auth_item);
        }

        echo json_encode($auth_arr);
    } else {
        echo json_encode(
            array('message' => 'No Authors Found')
        );
    }