

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    #search-bar {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 8px 12px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }

  </style>
</head>
<body>

  <h1>Simple Search</h1>

  <input type="text" id="search-bar" placeholder="Search...">
  <button id="search-button">Search</button>

  <?php
    // Sample data (replace with your database query or other data source)
    $items = [
      "apple", "banana", "cherry", "date", "elderberry", "fig"
    ];

    // Handle the search button click
    if (isset($_POST['search_term'])) {
      $searchTerm = htmlspecialchars($_POST['search_term']); // Sanitize input

      // Search logic - basic case-insensitive search
      $results = [];
      foreach ($items as $item) {
        if (stripos($item, $searchTerm) !== false) {
          $results[] = $item;
        }
      }

      // Display the results
      if (!empty($results)) {
        echo "<p>Search results for: " . $searchTerm . "</p>";
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for: " . $searchTerm . "</p>";
      }
    }
  ?>
