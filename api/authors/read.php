<?php
include_once('../../models/Author.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating author object
$object = new Author($db);

$authors = $object->read(); // calling read method

$row_count = $authors->rowCount(); // counting rows

if ($row_count > 0) {
  $post_array = array();

  while ($row = $authors->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $post_item = array(
      'id' => $id,
      'author' => $author,
    );
    // Push to "data"
    array_push($post_array, $post_item);
  }
  // OUTSIDE THE LOOP: turn array to JSON data
  echo json_encode($post_array);
} else {
  // if no authors are available
  $message = array('message' => 'No Authors Found');
  // Convert to JSON Data
  echo json_encode($message);
}
