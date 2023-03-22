<?php
include_once('../../models/Quote.php');
include_once('../../config/Database.php');
// connecting to database
$database = new Database();
$db = $database->connect();

$data = json_decode(file_get_contents("php://input")); // getting raw posted data

$message = array('message' => 'No Quotes Found');

if (isset($data->id) && is_numeric($data->id)) {
  // "id" field is set and is numeric, proceed
  $quote = new Quote($db);
  $quote->id = $data->id;

  if ($quote->delete()) {
    $quote_data = array('id' => $quote->id);
    echo json_encode($quote_data);
  } else {
    echo json_encode($message);
  }
} else {
  // "id" field is missing or not numeric
  echo json_encode($message);
}
