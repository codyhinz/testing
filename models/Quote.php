<?php

class Quote {
    //DB stuff
    private $conn;
    private $table = 'quotes';

    //Quote properties
    public $id;
    public $quote;
    public $category;
    public $author;
    public $authorId;
    public $categoryId;
    public $limit;

    //Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    //Get all quotes
    public function read() {
        //Create query
        $query = 'SELECT q.id,
                  q.quote,
                  a.author,
                  c.category 
                  FROM ' . $this->table . ' q
                  LEFT JOIN 
                   categories c ON q.categoryId = c.id
                  LEFT JOIN
                   authors a ON q.authorId = a.id
                  ORDER BY q.id';
        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }

    //Get single quote
    public function read_single() {
        //Create query
        $query = 'SELECT q.id,
                  q.quote,
                  a.author,
                  c.category 
                  FROM ' . $this->table . ' q
                  LEFT JOIN 
                   categories c ON q.categoryId = c.id
                  LEFT JOIN
                   authors a ON q.authorId = a.id
                  WHERE q.id = ?
                  LIMIT 0,1';
        $statement = $this->conn->prepare($query);
        $statement->bindValue(1, $this->id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if(empty($row['quote'])) {
            return;
        }
        //set properties
        $this->quote = $row['quote'];
        $this->author = $row['author'];
        $this->category = $row['category'];
    }

    //Create quote
    public function create() {
        //create query
        $query = 'INSERT INTO ' . $this->table . '
                 (quote, authorId, categoryId)
                  VALUES (:quote, :authorId, :categoryId)';
        $statement = $this->conn->prepare($query);

        //clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        //bind values and execute
        $statement->bindValue(':quote', $this->quote);
        $statement->bindValue(':authorId', $this->authorId);
        $statement->bindValue(':categoryId', $this->categoryId);
        if($statement->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
        
    }
    
    //update quote
    public function update() {
        //create query
        $query = 'UPDATE ' . $this->table . '
                 SET 
                    quote = :quote,
                    authorId = :authorId,
                    categoryId = :categoryId
                 WHERE id = :id';
        $statement = $this->conn->prepare($query);

        //clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //bind values and execute
        $statement->bindValue(':quote', $this->quote);
        $statement->bindValue(':authorId', $this->authorId);
        $statement->bindValue(':categoryId', $this->categoryId);
        $statement->bindValue(':id', $this->id);
        if($statement->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
        
    }

    //Delete quote
    public function delete() {
        //create query
        $query = 'DELETE FROM ' . $this->table . '
                  WHERE id = :id';
        $statement = $this->conn->prepare($query);

        //clean data 
        $this->id = htmlspecialchars(strip_tags($this->id));

        $statement->bindValue(':id', $this->id);
        if($statement->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    //get quotes by authorId, categoryId, both authorId or categoryId or limit
    public function get_quotes_by_query($authorId, $categoryId, $limit) {
        if($authorId && $categoryId) {
            $this->authorId = $authorId;
            $this->categoryId = $categoryId;
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a
                    ON a.id = q.authorId 
                  LEFT JOIN categories c
                    ON c.id = q.categoryId
                  WHERE a.id = :authorId
                  AND c.id = :categoryId
                  ORDER BY q.id';
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':authorId', $this->authorId);
            $statement->bindValue(':categoryId', $this->categoryId);
            $statement->execute();
            return $statement;
        } else if ($authorId) {
            $this->authorId = $authorId;
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a
                    ON a.id = q.authorId 
                  LEFT JOIN categories c
                    ON c.id = q.categoryId
                  WHERE a.id = :authorId
                  ORDER BY q.id';
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':authorId', $this->authorId);
            $statement->execute();
            return $statement;
        } else if ($categoryId) {
            $this->categoryId = $categoryId;
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a
                    ON a.id = q.authorId 
                  LEFT JOIN categories c
                    ON c.id = q.categoryId
                  WHERE c.id = :categoryId
                  ORDER BY q.id';
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':categoryId', $this->categoryId);
            $statement->execute();
            return $statement;
        } else if ($limit) {
            $this->limit = $limit;
            $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a
                    ON a.id = q.authorId 
                  LEFT JOIN categories c
                    ON c.id = q.categoryId
                  ORDER BY q.id
                  LIMIT ' . $this->limit;
            $statement = $this->conn->prepare($query);
            $statement->execute();
            return $statement;
        }
    }

    public function get_quotes_for_view($authorId, $categoryId) {
        $num = 0;
        //if authorId and/or categoryId are provided in url query
        //get quotes by query
        $limit = null;
        if($authorId || $categoryId) {
            $result = $this->get_quotes_by_query($authorId, $categoryId, $limit);
            $num = $result->rowCount();
        } else {
            //otherwise get all quotes
            $result = $this->read();
            //Get row count
            $num = $result->rowCount();
        }
        
         //quotes array
         $quotes_arr = array();
         
        //Check if any quotes
        if($num > 0) {

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
        } 
        return $quotes_arr;

    }
}