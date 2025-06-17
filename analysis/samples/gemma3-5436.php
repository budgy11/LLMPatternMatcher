
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Ensures padding doesn't affect width */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
  </div>


  <?php
    // Example data - Replace with your actual data source
    $data = [
      "apple", "banana", "cherry", "date", "elderberry", "fig"
    ];


    if (isset($_GET['search']) && !empty($_GET['search'])) {
      $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input

      $results = [];
      foreach ($data as $item) {
        if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
          $results[] = $item;
        }
      }

      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
      }

    } else {
      echo "<p>Enter your search term above.</p>";
    }
  ?>
