

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Let's use a placeholder for the database connection

// Example:
// $db = mysqli_connect("localhost", "username", "password", "database_name");
// if (!$db) {
//     die("Connection failed: " . mysqli_connect_error());
// }


// **Basic Search Function**
function searchDatabase($search_term, $table_name, $column_to_search = 'name') {
  //  Replace this with your actual database connection and query
  //  This is a simplified example using a hardcoded array of data.
  //  You should replace this with your database query.

  $data = [
    ['id' => 1, 'name' => 'Apple', 'description' => 'A red fruit'],
    ['id' => 2, 'name' => 'Banana', 'description' => 'A yellow fruit'],
    ['id' => 3, 'name' => 'Orange', 'description' => 'A citrus fruit'],
    ['id' => 4, 'name' => 'Grape', 'description' => 'Small, sweet fruit']
  ];


  $results = [];

  foreach ($data as $row) {
    // Perform case-insensitive search
    if (stripos($row[$column_to_search], $search_term) !== false) {
      $results[] = $row;
    }
  }

  return $results;
}


// **Search Bar HTML Form**
?>
