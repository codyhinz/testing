<?php

    require('config/database.php');
    require('models/quote.php');
    require('models/author.php');
    require('models/category.php');

    $database = new Database();
    $db = $database->connect();

    $quote = new Quote($db);
    $author = new Author($db);
    $category = new Category($db);

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