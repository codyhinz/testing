<?php

    require('config/Database.php');
    require('models/Quote.php');
    require('models/Author.php');
    require('models/Category.php');

    //Instantiate DB & Connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate objects
    $quote = new Quote($db);
    $author = new Author($db);
    $category = new Category($db);

    //get url query parameters
    $authorId = filter_input(INPUT_GET, 'authorId', FILTER_VALIDATE_INT);
    $categoryId = filter_input(INPUT_GET, 'categoryId', FILTER_VALIDATE_INT);

    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
    if(!$action) {
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if(!$action) {
            $action = "list_all_quotes";
        }
    } 

    switch($action) {
        case 'list_all_quotes':
            $authors = $author->get_authors_for_view();
            $categories = $category->get_categories_for_view();
            $quotes = $quote->get_quotes_for_view($authorId, $categoryId);
            include('view/list_quotes.php');
            break;
    }