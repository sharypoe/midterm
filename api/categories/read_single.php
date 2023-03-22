<?php
include_once('../../models/Category.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating category object
$object = new Category($db);

// get id from http
$object->id = isset($_GET['id']) ? $_GET['id'] : die();

$object->read_single();

if (!$object->category) // if the category does not exist, display message
{
  // create a message
  $message = array('message' => 'category_id Not Found');
  // Convert to JSON Data
  echo json_encode($message);
} else // if the category_id exists, then display both
{
  // create an array
  $single_post = array(
    'id' => $object->id,
    'category' => $object->category,
  );
  // Convert to JSON Data
  echo json_encode($single_post);
}
