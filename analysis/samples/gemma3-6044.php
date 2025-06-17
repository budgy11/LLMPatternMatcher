

<?php

// Assuming you have a database connection established (e.g., using mysqli)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if (!$db->connect_error) {
//   // Handle connection error
// }

// Function to handle search queries
function searchData($search_term, $data) {
  $results = [];

  foreach ($data as $item) {
    // Normalize the search term to lowercase for case-insensitive search
    $search_term = strtolower($search_term);

    // Check if the search term exists in any of the item's fields.
    // Modify this section based on the fields you want to search in.
    if (strpos(strtolower($item['title']), $search_term) !== false ||  // Check title
        strpos(strtolower($item['description']), $search_term) !== false || // Check description
        strpos(strtolower($item['name']), $search_term) !== false) { // Check name
      $results[] = $item;
    }
  }

  return $results;
}


// Example Data (replace with your actual data from the database)
$items = [
  ['id' => 1, 'title' => 'Awesome Product', 'description' => 'A great product to buy.', 'name' => 'Product A'],
  ['id' => 2, 'title' => 'Another Product', 'description' => 'A product with a unique feature.', 'name' => 'Product B'],
  ['id' => 3, 'title' => 'Cool Gadget', 'description' => 'A very cool gadget!', 'name' => 'Product C'],
];

// Get the search term from the input field (assuming you have an input field with id="search")
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$search_results = searchData($search_term, $items);

?>
