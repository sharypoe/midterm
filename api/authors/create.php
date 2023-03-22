<?php
include_once('../../models/Author.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating author object
$object = new Author($db);

$message = array('message' => 'Missing Required Parameters');

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

// Check if the required fields are present
if (!property_exists($data, 'author')) {
  echo json_encode($message);
  exit;
}

// Check if the data type is not correct
if (!is_string($data->author)) {
  echo json_encode($message);
  exit;
}

$object->author = $data->author;

// Create object
if ($object->create()) {
  $author_data = array(
    'id' => $object->id,
    'author' => $object->author
  );
  echo json_encode($author_data);
} else {
  echo json_encode($message);
}
