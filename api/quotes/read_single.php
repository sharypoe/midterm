<?php
include_once('../../models/Quote.php');
include_once('../../config/Database.php');

// connecting to database
$database = new Database();
$db = $database->connect();

// instantiating quote object
$object = new Quote($db);
$quote = $object->read_single();

if ($quote) {
  // OUTSIDE THE FUNCTION: create array for the single quote
  $quote_item = array(
    'id' => $quote['id'],
    'quote' => $quote['quote'],
    'author' => $quote['author'],
    'category' => $quote['category']
  );
  // OUTSIDE THE ARRAY: turn array to JSON data
  echo json_encode($quote_item);
} else {
  // if no quote is available, then display message
  $message = array('message' => 'No Quotes Found');
  // Convert to JSON Data
  echo json_encode($message);
}
