<?php
include_once('../../models/Category.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating category object
$object = new Category($db);

$categories = $object->read(); // calling read method

$row_count = $categories->rowCount(); // counting rows

if ($row_count > 0) {
  $post_array = array();

  while ($row = $categories->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $post_item = array(
      'id' => $id,
      'category' => $category,
    );
    // Push to "data"
    array_push($post_array, $post_item);
  }
  // OUTSIDE THE LOOP: turn array to JSON data
  echo json_encode($post_array);
} else {
  // if no categories are available
  $message = array('message' => 'No Categories Found');
  // Convert to JSON Data
  echo json_encode($message);
}
