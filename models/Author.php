<?php
class Author
{

  private $conn;
  private $table = "authors";

  // properties of table "authors"
  public $id;
  public $author;

  // HELPER FUNCTIONS:
  public function exists()
  {
    // checking if it exists
    $query = "SELECT id FROM " . $this->table . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $id = htmlspecialchars(strip_tags($this->id)); // sanitizing
    $stmt->bindParam(1, $id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
      // If the id does not exist, returns false
      return false;
    }
    return true;
  }

  public function DisplayError($stmt) // I know that this function might be overdoing it a little, but I still 
  // will have it just in case something goes wrong
  {
    $message = array('message' => 'Error: ' . $stmt->errorInfo()[2]);
    // Convert to JSON Data
    echo json_encode($message);
  }
  // END OF HELPER FUNCTIONS

  public function __construct($db)
  {
    // we'll simply pass it a database object. This is why we must return the database public function connect
    $this->conn = $db; // this is why OOP is important. We won't need to repeat ourselves
  }

  public function read() // reads all
  {
    $query = "SELECT * FROM " . $this->table; // selects all authors with their accompanying id
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  public function read_single()
  {
    // Sanitize the input parameter
    $author_id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT id, author FROM " . $this->table . " WHERE id = :author_id";

    // prepare statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":author_id", $author_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->id     = $row["id"];
      $this->author = $row["author"];
    }
  }

  public function create()
  {
    // Create a query (note that id is set automatically)
    $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean up data (sanitize data)
    $this->author = htmlspecialchars(strip_tags($this->author));

    // bind data
    $stmt->bindParam(':author', $this->author);

    // execute query
    if ($stmt->execute()) {
      return true;
    }
    // if something else goes wrong (I do this multiple times, and although it might not be necessary,
    // I'll leave it anyway)
    $this->DisplayError($stmt);
    return false;
  }

  public function delete()
  {
    // checks if the id to delete exists
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
    // in order to update an author, first check if id exists and is valid
    if ($this->exists() == false) {
      return false; // if it does not exist, then you can't update anything
    }

    // Create query:
    $query =  "UPDATE " . $this->table . " SET author = :author WHERE id = :id";

    // prepare statement
    $stmt = $this->conn->prepare($query);

    // sanitize data
    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // bind data
    $stmt->bindParam(':author', $this->author);
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
