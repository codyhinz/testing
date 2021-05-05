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
    $category = new Category($db);

    //Category query
    $result = $category->read();
    //Get row count
    $num = $result->rowCount();

    //Check if any categories
    if($num > 0) {
        //categories array
        $category_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            //Push item to categories array
            array_push($category_arr, $category_item);
        }

        //Turn to JSON & output
        echo json_encode($category_arr);
    } else {
        //no categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }