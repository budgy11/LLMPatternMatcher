

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      width: 300px;
    }
    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    button {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("search-input").value;
      if (searchTerm) {
        // Perform your search logic here.
        // This is a placeholder.  Replace this with your actual search implementation.

        // Example:  Display the search term in an alert
        alert("You searched for: " + searchTerm);

        // Example:  You could filter an array of data here based on the searchTerm
        // This requires that you have an array of data, e.g.,  my_data = ["apple", "banana", "orange", "grape"];
        // and then:
        // var filteredData = my_data.filter(function(item) {
        //   return item.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1;
        // });
        // console.log(filteredData);
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using mysqli or PDO)
// Let's assume a mysqli connection for this example:
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }

// Example data (replace with your actual data source - database query)
$data = [
    ["id" => 1, "name" => "Apple", "description" => "A sweet fruit."],
    ["id" => 2, "name" => "Banana", "description" => "A yellow fruit."],
    ["id" => 3, "name" => "Orange", "description" => "A citrus fruit."],
    ["id" => 4, "name" => "Grape", "description" => "Small, juicy fruit."],
];

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = trim($searchTerm); // Remove leading/trailing whitespace

// Escape the search term to prevent SQL injection
$searchTerm = $db->real_escape_string($searchTerm);

// Perform the search
$searchResults = [];

if ($searchTerm !== "") {
    foreach ($data as $item) {
        // Case-insensitive search
        $name = strtolower($item['name']);
        $description = strtolower($item['description']);

        if (str_contains($name, $searchTerm) || str_contains($description, $searchTerm)) {
            $searchResults[] = $item;
        }
    }
}


?>
