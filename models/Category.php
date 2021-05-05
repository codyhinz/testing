<?php

class Category {
    private $conn;
    private $table = 'categories';
    
    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id, category
                FROM ' . $this->table .'
                ORDER BY id';
        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }


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

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category)
                  VALUES (:category)';
        $statement = $this->conn->prepare($query);

        $this->category = htmlspecialchars(strip_tags($this->category));

        $statement->bindValue(':category', $this->category);
        if($statement->execute()) {
            return true;
        }
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                 SET category = :category
                 WHERE id = :id';
        $statement = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':category', $this->category);
        if($statement->execute()) {
            return true;
        }
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . '
                  WHERE id = :id';
        $statement = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $statement->bindValue(':id', $this->id);
        if($statement->execute()) {
            return true;
        }
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    public function get_categories_for_view() {
        $result = $this->read();
        $num = $result->rowCount();
        $cat_arr = array();
        if($num > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $cat_item = array(
                    'id' => $id,
                    'category' => $category
                );

                array_push($cat_arr, $cat_item);
            }
        } 
        return $cat_arr;
    }
}