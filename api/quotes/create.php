<?php
include_once('../../models/Quote.php');
include_once('../../config/Database.php');

// Connect to database
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are present
if (!property_exists($data, 'quote') || !property_exists($data, 'author_id') || !property_exists($data, 'category_id')) {
  $message = array('message' => 'Missing Required Parameters');
  echo json_encode($message);
  exit;
}

// Set Quote properties
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;


// Create the quote
if ($quote->create()) {
  // Get the newly created quote
  $new_quote = array(
    'id' => $quote->id,
    'quote' => $quote->quote,
    'author_id' => $quote->author_id,
    'category_id' => $quote->category_id
  );
  echo json_encode($new_quote);
} else {
  // message will already be displayed here, if create() fails
}
