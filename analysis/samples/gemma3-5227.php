
<!DOCTYPE html>
<html>
<head>
<title>PHP Search Bar with Database</title>
</head>
<body>

<div class="search-container">
  <input type="text" id="searchInput" placeholder="Search Products...">
  <button onclick="search()">Search</button>
</div>

<script>
  function search() {
    var searchTerm = document.getElementById("searchInput").value;

    // AJAX (Asynchronous JavaScript and XML) -  Example (you'll need to adapt this to your setup)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php");  //  The URL of your PHP file
    xhr.send(JSON.stringify({searchInput: searchTerm}));

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.products) {
          // Display the products (using response.products)
          console.log(response.products);
        } else {
          console.log("No products found");
        }
      } else {
        console.error("Error:", xhr.status);
      }
    }
  }
</script>
</body>
</html>


<?php

// Sample data (replace with your actual data source - e.g., database query)
$data = [
  "apple", "banana", "cherry", "date", "elderberry", "fig", "grape",
  "honeydew", "kiwi", "lemon", "mango", "nectarine", "orange", "papaya", "raspberry",
  "strawberry", "tangerine", "watermelon"
];

// Function to handle the search
function searchData($searchTerm, $data) {
  $results = [];
  $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive search

  foreach ($data as $item) {
    if (stristr($item, $searchTerm)) { // Use stristr for case-insensitive comparison
      $results[] = $item;
      break; // Stop searching after finding the first match
    }
  }

  return $results;
}

// Get the search term from the form (if submitted) or use a default
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
