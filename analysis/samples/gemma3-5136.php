
  <script>
    // Optional: Add some styling to the search bar
    document.querySelector("input[type='text']").placeholder = "Search...";
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding doesn't affect width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
  </div>


  <?php
  // Example: Search through an array of data (replace with your data source)
  $data = [
    "Apple",
    "Banana",
    "Orange",
    "Grapefruit",
    "Strawberry",
    "Kiwi",
    "Pineapple",
    "Mango"
  ];


  if (isset($_GET['search_term'])) {
    $searchTerm = htmlspecialchars($_GET['search_term']); // Sanitize input

    // Filter the data based on the search term
    $searchResults = array_filter($data, function ($item) use ($searchTerm) {
      return stripos($item, $searchTerm) !== false; // Case-insensitive search
    });

    // Display the results
    if (count($searchResults) > 0) {
      echo "<h2>Search Results:</h2>";
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found.</p>";
    }
  }
?>
