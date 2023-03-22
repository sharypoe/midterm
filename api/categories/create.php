<?php
include_once('../../models/Category.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$object = new Category($db);

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

$message = array('message' => 'Missing Required Parameters');

// Check if the required fields are present
if (!property_exists($data, 'category')) {
  echo json_encode($message);
  exit;
}

// Check if the data type is not correct
if (!is_string($data->category)) {
  echo json_encode($message);
  exit;
}

$object->category = $data->category;

// Create object
if ($object->create()) {
  $category_data = array(
    'id' => $object->id,
    'category' => $object->category
  );
  echo json_encode($category_data);
} else {
  echo json_encode($message);
}
