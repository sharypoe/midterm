<?php
include_once('../../models/Author.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

$message = array('message' => 'Missing Required Parameters');

if (isset($data->id) && is_numeric($data->id)) {
  // "id" field is set and is numeric, proceed
  $author = new Author($db);
  $author->id = $data->id;

  if ($author->delete()) {
    $author_data = array('id' => $author->id);
    echo json_encode($author_data);
  } else {
    $message = (array("message" => "author_id Not Found"));
    echo json_encode($message);
  }
} else {
  // "id" field is missing or not numeric
  echo json_encode($message);
}
