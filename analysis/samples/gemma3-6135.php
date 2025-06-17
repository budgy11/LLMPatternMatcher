
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px; /* Adjust as needed */
      margin: 0 auto;
      text-align: center;
    }
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Include padding and border in the element's total width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>

  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      // Get the value of the input field
      var searchTerm = document.getElementById('searchInput').value.toLowerCase();

      // Perform your search logic here
      // This is a basic example that just alerts the search term
      alert("Searching for: " + searchTerm);

      // Example of filtering an array (replace with your actual data)
      // var data = ["apple", "banana", "orange", "grape", "avocado"];
      // var filteredData = data.filter(function(item) {
      //   return item.toLowerCase().indexOf(searchTerm) > -1;
      // });
      // console.log(filteredData); // Output to the console
    });
  </script>

</body>
</html>


// Assuming you have an array of strings called $data
var data = ["apple", "banana", "orange", "grape", "avocado"];
var searchTerm = document.getElementById('searchInput').value.toLowerCase();

var filteredData = data.filter(function(item) {
  return item.toLowerCase().indexOf(searchTerm) > -1;
});


<?php

// Configuration
$search_term = ""; // Default search term
$results = [];      // Array to store search results (replace with your data source)

// Function to handle the search
function search($search_term, $data) {
    global $results;

    $results = []; // Clear previous results

    if (empty($search_term)) {
        return $results; // Return empty results if no search term
    }

    $search_term = trim($search_term); // Remove leading/trailing whitespace

    foreach ($data as $item) {
        // Adapt this logic based on your data structure.
        // This example assumes your data is an array of associative arrays:
        // e.g., ['title' => 'Product A', 'description' => '...', 'id' => 123]

        if (
            strpos($item['title'], $search_term) !== false ||  // Check title
            strpos($item['description'], $search_term) !== false // Check description
        ) {
            $results[] = $item;
        }
    }
    return $results;
}


// Example Data (Replace with your actual data source)
$products = [
    ['title' => 'Laptop X1', 'description' => 'Powerful laptop for work and gaming.', 'id' => 1],
    ['title' => 'Wireless Mouse', 'description' => 'Ergonomic wireless mouse.', 'id' => 2],
    ['title' => 'Keyboard Pro', 'description' => 'Mechanical keyboard with RGB lighting.', 'id' => 3],
    ['title' => 'Monitor 27"', 'description' => '27-inch LED monitor for crisp visuals.', 'id' => 4],
    ['title' => 'Headphones Z900', 'description' => 'Noise-cancelling headphones with excellent sound quality.', 'id' => 5]
];


// Handle the search form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = isset($_POST['search_term']) ? trim($_POST['search_term']) : "";
    search($search_term, $products); // Call the search function
}

?>
