<?php
include_once('../../models/Category.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

$message = array('message' => 'Missing Required Parameters');

if (isset($data->id) && is_numeric($data->id)) {
  // "id" field is set and is numeric, proceed
  $category = new Category($db);
  $category->id = $data->id;

  if ($category->delete()) {
    $category_data = array('id' => $category->id);
    echo json_encode($category_data);
  } else {
    $message = (array("message" => "category_id Not Found"));
    echo json_encode($message);
  }
} else {
  // "id" field is missing or not numeric
  echo json_encode($message);
}
