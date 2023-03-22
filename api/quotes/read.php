<?php
// need to display id, quote, author, and category
include_once('../../models/Quote.php');
include_once('../../config/Database.php');

// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating quote object
$object = new Quote($db);

$quotes = $object->read(); // calling read method

$row_count = $quotes->rowCount(); // counting rows

if ($row_count > 0) {
  $post_array = array();

  while ($row = $quotes->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $post_item = array(
      'id' => $id,
      'quote' => $quote,
      'author' => $author,
      'category' => $category
    );
    // Push to "data"
    array_push($post_array, $post_item);
  }
  // OUTSIDE THE LOOP: turn array to JSON data
  echo json_encode($post_array);
} else {
  // if no quotes are available
  $message = array('message' => 'No Quotes Found');
  // Convert to JSON Data
  echo json_encode($message);
}
