<?php
class Quote
{
  private $conn;
  private $table = "quotes";

  // properties of table "quotes"
  public $id;
  public $quote;
  public $author_id;
  public $category_id;

  // MY HELPER FUNCTIONS
  public function exists($table, $value) // since I'll likely use it for three different tables
  {
    // checking if it exists
    $query = "SELECT id FROM " . $table . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $id = htmlspecialchars(strip_tags($value));
    $stmt->bindParam(1, $id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
      // If the id does not exist, returns false
      return false;
    }
    return true;
  }

  public function DisplayError($stmt) // may not be necessary, but still including it
  {
    $message = array('message' => 'Error: ' . $stmt->errorInfo()[2]);
    // Convert to JSON Data
    echo json_encode($message);
  }

  // END OF HELPER FUNCTIONS

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = 'SELECT q.id, q.quote, a.author, c.category
                FROM ' . $this->table .  ' q 
                JOIN authors a ON q.author_id = a.id
                JOIN categories c ON q.category_id = c.id';
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function read_single()
  {
    $query = 'SELECT q.id, q.quote, a.author, c.category
      FROM ' . $this->table .  ' q 
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id';

    // checking what the user entered
    if (isset($_GET['id'])) {
      $id = htmlspecialchars(strip_tags($_GET['id'])); // sanitize data
      $query .= ' WHERE q.id = ' . $id;
    } else {
      return null;
    }

    // Prepare statement
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function create()
  {
    // This was VERY difficult to make it work

    // Checking if author_id exists in authors table
    if (!$this->exists("authors", $this->author_id)) {
      $message = array('message' => 'author_id Not Found');
      echo json_encode($message);
      return false;
    }

    // Checking if category_id exists in categories table
    if (!$this->exists("categories", $this->category_id)) {
      $message = array('message' => 'category_id Not Found');
      echo json_encode($message);
      return false;
    }

    // Checking if required parameters are present
    if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
      $message = array('message' => 'Missing Required Parameters');
      echo json_encode($message);
      return false;
    }

    // Create query
    $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean up data (sanitize data)
    $quote = htmlspecialchars(strip_tags($this->quote));
    $author_id = htmlspecialchars(strip_tags($this->author_id));
    $category_id = htmlspecialchars(strip_tags($this->category_id));

    // bind data
    $stmt->bindParam(':quote', $quote);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':category_id', $category_id);

    // execute query
    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }

    // print error if something went wrong
    $this->DisplayError($stmt);
    return false;
  }

  public function update()
  {
    // even more difficult to make it work

    // Checking if required parameters are present
    if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
      $message = array('message' => 'Missing Required Parameters');
      echo json_encode($message);
      return false;
    }

    // Check if quote exists (by id)
    if (!$this->exists("quotes", $this->id)) {
      $message = array('message' => 'No Quotes Found');
      echo json_encode($message);
      return false;
    }

    // Checking if author_id exists in authors table
    if (!$this->exists("authors", $this->author_id)) {
      $message = array('message' => 'author_id Not Found');
      echo json_encode($message);
      return false;
    }

    // Checking if category_id exists in categories table
    if (!$this->exists("categories", $this->category_id)) {
      $message = array('message' => 'category_id Not Found');
      echo json_encode($message);
      return false;
    }

    // Create query
    $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean up data (sanitize data)
    $quote = htmlspecialchars(strip_tags($this->quote));
    $author_id = htmlspecialchars(strip_tags($this->author_id));
    $category_id = htmlspecialchars(strip_tags($this->category_id));
    $id = htmlspecialchars(strip_tags($this->id));

    // bind data
    $stmt->bindParam(':quote', $quote);
    $stmt->bindParam(':author_id', $author_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':id', $id);

    // execute query
    if ($stmt->execute()) {
      return true;
    }

    // print error if something went wrong
    $this->DisplayError($stmt);
    return false;
  }

  public function delete()
  {
    // checks if id exists
    if ($this->exists($this->table, $this->id) == false) {
      return false;
    }

    // Create query
    $query = "DELETE FROM " . $this->table . " WHERE id = :id";

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind parameter
    $stmt->bindParam(':id', $this->id);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }
    // if something else goes wrong
    $this->DisplayError($stmt);
    return false;
  }
}
