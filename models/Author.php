<?php

class Author {
    //DB stuff
    private $conn;
    private $table = 'authors';

    //public properties
    public $id;
    public $author;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get authors
    public function read() {
        $query = 'SELECT id,author
                  FROM ' . $this->table . '
                  ORDER BY id';
        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }

    //get single author
    public function read_single() {
        $query = 'SELECT id,author
                  FROM ' . $this->table . '
                  WHERE id = ?';
        $statement = $this->conn->prepare($query);
        $statement->bindValue(1, $this->id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if(empty($row['author'])) {
            return;
        }
        $this->author = $row['author'];
    }

    //create author
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author)
                 VALUES (:author)';
        $statement = $this->conn->prepare($query);
        //clean data
        $this->author = htmlspecialchars(strip_tags($this->author));
        //bind and execute
        $statement->bindValue(':author', $this->author);
        if($statement->execute()) {
            return true;
        }
        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    //update author
    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET author = :author
                  WHERE id = :id';
        $statement = $this->conn->prepare($query);
        
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));

        //bind and execute
        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':author', $this->author);
        if($statement->execute()) {
            return true;
        }
        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);
        return false;
    }

    //delete author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . '
                  WHERE id = :id';
        $statement = $this->conn->prepare($query);
        
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        //bind and execute
        $statement->bindValue(':id', $this->id);
        if($statement->execute()) {
            return true;
        }
        printf("Error: $s.\n", $statement->error);
        return false;
    }

    public function get_authors_for_view() {
        $result = $this->read();
        $num = $result->rowCount();
        //authors array
        $auth_arr = array();
        //Check if any authors
        if($num > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $auth_item = array(
                    'id' => $id,
                    'author' => $author
                );

                //Push item to authors array
                array_push($auth_arr, $auth_item);
            }
        } 
        return $auth_arr;
    }

}
