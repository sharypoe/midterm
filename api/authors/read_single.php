<?php
include_once('../../models/Author.php');
include_once('../../config/Database.php');

// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating author object
$object = new Author($db);

// get id from http
$object->id = isset($_GET['id']) ? $_GET['id'] : die();

// get author
$object->read_single();

if (!$object->author) // if the author does not exist, display message
{
  // create a message
  $message = array('message' => 'author_id Not Found');
  // Convert to JSON Data
  echo json_encode($message);
} else // if the author_id exists, then display both
{
  // create an array
  $single_post = array(
    'id' => $object->id,
    'author' => $object->author,
  );
  // Convert to JSON Data
  echo json_encode($single_post);
}
