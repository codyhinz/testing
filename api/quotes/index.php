<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    require '../../config/Database.php';
    require '../../models/Quote.php';

    //Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quote object
    $quote = new Quote($db);

    //get url query parameters
    $authorId = filter_input(INPUT_GET, 'authorId', FILTER_VALIDATE_INT);
    $categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_VALIDATE_INT);
    $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);

    $num = 0;

    //if authorId, categoryId, or limit provided in url query
    //get quotes by query
    if($authorId || $categoryId || $limit) {
        $result = $quote->get_quotes_by_query($authorId, $categoryId, $limit);
        $num = $result->rowCount();
    } else {
        //otherwise get all quotes
        $result = $quote->read();
        //Get row count
        $num = $result->rowCount();
    }
    
    //Check if any quotes
    if($num > 0) {
        //quotes array
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            //Push item to quotes array
            array_push($quotes_arr, $quote_item);
        }

        //Turn to JSON & output
        echo json_encode($quotes_arr);
    } else {
        //no quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }