<?php
class Category
{

  private $conn;
  private $table = "categories";

  // properties of table "categories"
  public $id;
  public $category;

  // HELPER FUNCTIONS:
  public function exists()
  {
    // checking if it exists
    $query = "SELECT id FROM " . $this->table . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(1, $id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
      // If the id does not exist, returns false
      return false;
    }
    return true;
  }

  public function DisplayError($stmt) // may be over doing it, but I'll keep it
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

  public function read() // reads all
  {
    $query = "SELECT * FROM " . $this->table; // selects all categories with their accompanying id
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function read_single()
  {
    // Sanitize the input parameter
    $category_id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT id, category FROM " . $this->table . " WHERE id = :category_id";

    // prepare statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->id     = $row["id"];
      $this->category = $row["category"];
    }
  }

  public function create()
  {
    // Create a query (note that id is set automatically)
    $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean up data (sanitize data)
    $this->category = htmlspecialchars(strip_tags($this->category));

    // bind data
    $stmt->bindParam(':category', $this->category);

    // execute query
    if ($stmt->execute()) {
      return true;
    }
    // if something else went wrong
    $this->DisplayError($stmt);
    return false;
  }

  public function delete()
  {
    // checks if id to delete exists
    if ($this->exists() == false) {
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

  public function update()
  {
    // in order to update a category, first check if id exists and is valid
    if ($this->exists() == false) {
      return false; // if it does not exist, then you can't update anything
    }
    // Create query:
    $query =  "UPDATE " . $this->table . " SET category = :category WHERE id = :id";

    // prepare statement
    $stmt = $this->conn->prepare($query);

    // sanitize data
    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // bind data
    $stmt->bindParam(':category', $this->category);
    $stmt->bindParam(':id', $this->id);

    // execute query
    if ($stmt->execute()) {
      return true;
    }

    //if something else goes wrong
    $this->DisplayError($stmt);
    return false;
  }
}
