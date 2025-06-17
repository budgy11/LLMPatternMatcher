

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 0 auto;
      margin-top: 20px;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
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
    <button onclick="performSearch()">Search</button>
  </div>

  <script>
    function performSearch() {
      const searchTerm = document.getElementById('search-input').value.toLowerCase();
      const items = document.querySelectorAll('.search-item'); // Replace with your actual data source

      items.forEach(item => {
        if (item.textContent.toLowerCase().includes(searchTerm)) {
          item.style.display = 'block'; // Or however you want to highlight results
        } else {
          item.style.display = 'none';
        }
      });
    }
  </script>

  <!-- Example Data (Replace with your actual data) -->
  <div class="search-item">Item 1 - This is a test item.</div>
  <div class="search-item">Item 2 - Another item for testing.</div>
  <div class="search-item">Item 3 -  Testing with different keywords.</div>
  <div class="search-item">Item 4 -  This is completely unrelated.</div>

  <style>
    .search-item {
      margin-bottom: 10px;
      border: 1px solid #eee;
      padding: 10px;
      display: none; // Initially hide all items
    }
  </style>
</body>
</html>


<?php

// Assuming you have a database connection established
// (e.g., using mysqli or PDO)
// $db = new mysqli("localhost", "username", "password", "database_name");
// if ($db->connect_error) {
//   die("Connection failed: " . $db->connect_error);
// }


// **Simplified Example - No Database Connection**
// This example demonstrates a basic search bar without connecting to a database.
// It's for demonstration purposes and will only search through a static array.

$search_terms = array(
    "apple",
    "banana",
    "orange",
    "grape",
    "mango",
    "pineapple",
    "strawberry"
);

if (isset($_GET["search"])) {
    $search_term = trim($_GET["search"]);
    if (empty($search_term)) {
        $results = []; // Clear results if the search term is empty
    } else {
        $results = [];
        foreach ($search_terms as $term) {
            if (stripos($term, $search_term) !== false) { // Case-insensitive search
                $results[] = $term;
            }
        }
    }
} else {
    $results = []; // No search term provided
}

?>
