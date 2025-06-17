

<?php

// Assuming you have a database connection established (e.g., mysqli)
// You'll need to replace these placeholders with your actual connection details.
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// $connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// if (!$connection) {
//   die("Connection failed: " . mysqli_connect_error());
// }

//  Simulate database connection for demonstration purposes if you don't have one.
$data = [
    ["id" => 1, "name" => "Apple"],
    ["id" => 2, "name" => "Banana"],
    ["id" => 3, "name" => "Orange"],
    ["id" => 4, "name" => "Grape"],
];

// Function to perform the search
function searchData($searchTerm, $data) {
  $results = [];
  foreach ($data as $row) {
    if (strtolower($row["name"]) == strtolower($searchTerm)) {
      $results[] = $row;
    }
  }
  return $results;
}


// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$searchResults = searchData($searchTerm, $data);


?>
