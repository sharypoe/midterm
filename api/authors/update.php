<?php
include_once('../../models/Author.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

// two things to watch out for: 
// id must be numeric and must be set, and it must also have
// a proper author and value. 
$message = array('message' => 'Missing Required Parameters');

if (!property_exists($data, 'author') || (!is_string($data->author)) || (!isset($data->id)) || (!is_numeric($data->id))) {
  echo json_encode($message);
} else {
  // set ID and author to update
  $object = new Author($db);
  $object->id = $data->id;
  $object->author = $data->author;

  // update Author
  if ($object->update()) {
    $author_data = array(
      'id' => $object->id,
      'author' => $object->author
    );
    echo json_encode($author_data);
  } else {
    echo json_encode($message);
  }
}
