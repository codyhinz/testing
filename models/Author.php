<?php

class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT id,author
                  FROM ' . $this->table . '
                  ORDER BY id';
        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }

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

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author)
                 VALUES (:author)';
        $statement = $this->conn->prepare($query);
        $this->author = htmlspecialchars(strip_tags($this->author));
        $statement->bindValue(':author', $this->author);
        if($statement->execute()) {
            return true;
        }
        printf("Error: $s.\n", $statement->error);

        return false;
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET author = :author
                  WHERE id = :id';
        $statement = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->author = htmlspecialchars(strip_tags($this->author));

        $statement->bindValue(':id', $this->id);
        $statement->bindValue(':author', $this->author);
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

    public function get_authors_for_view() {
        $result = $this->read();
        $num = $result->rowCount();
        $auth_arr = array();
        if($num > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $auth_item = array(
                    'id' => $id,
                    'author' => $author
                );

                array_push($auth_arr, $auth_item);
            }
        } 
        return $auth_arr;
    }

}
