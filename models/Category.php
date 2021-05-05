<?php

class Category {
    //DB stuff
    private $conn;
    private $table = 'categories';
    
    //Properties
    public $id;
    public $category;

    //constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    //get categories
    public function read() {
        //create query
        $query = 'SELECT id, category
                FROM ' . $this->table .'
                ORDER BY id';
        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }

    //get single category
    public function read_single() {
        $query = 'SELECT id, category
                  FROM ' . $this->table . ' 
                  WHERE id = ?';
        $statement = $this->conn->prepare($query);
        $statement->bindValue(1, $this->id);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if(empty($row['category'])) {
            return;
        }
        $this->category = $row['category'];
    }

    //create category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category)
                  VALUES (:category)';
        $statement = $this->conn->prepare($query);

        //clean data
        $this->category = htmlspecialchars(strip_tags($this->category));

        //bind value and execute
        $statement->bindValue(':category', $this->category);
        if($statement->execute()) {
            return true;
        }
        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    //update category
    public function update() {
        $query = 'UPDATE ' . $this->table . '
                 SET category = :category
                 WHERE id = :id';
        $statement = $this->conn->prepare($query);
        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));
        //bind values and execute
        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':category', $this->category);
        if($statement->execute()) {
            return true;
        }
        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    //delete category
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
        //print error if something goes wrong
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    public function get_categories_for_view() {
        $result = $this->read();
        $num = $result->rowCount();
        //authors array
        $cat_arr = array();
        //Check if any authors
        if($num > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $cat_item = array(
                    'id' => $id,
                    'category' => $category
                );

                //Push item to categories array
                array_push($cat_arr, $cat_item);
            }
        } 
        return $cat_arr;
    }
}