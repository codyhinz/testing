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

    //Author query
    $result = $auth->read();
    //Get row count
    $num = $result->rowCount();

    //Check if any authors
    if($num > 0) {
        //authors array
        $auth_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $auth_item = array(
                'id' => $id,
                'author' => $author
            );

            //Push item to categories array
            array_push($auth_arr, $auth_item);
        }

        //Turn to JSON & output
        echo json_encode($auth_arr);
    } else {
        //no categories
        echo json_encode(
            array('message' => 'No Authors Found')
        );
    }