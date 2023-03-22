<?php
include_once('../../models/Category.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

// two things to watch out for: 
// id must be numeric and must be set, and it must also have
// a proper category and value. 
$message = array('message' => 'Missing Required Parameters');

if (!property_exists($data, 'category') || (!is_string($data->category)) || (!isset($data->id)) || (!is_numeric($data->id))) {
  echo json_encode($message);
} else {
  // set ID and category to update
  $object = new Category($db);
  $object->id = $data->id;
  $object->category = $data->category;

  // update category
  if ($object->update()) {
    $category_data = array(
      'id' => $object->id,
      'category' => $object->category
    );
    echo json_encode($category_data);
  } else {
    echo json_encode($message);
  }
}
