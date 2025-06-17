
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      margin-bottom: 10px;
    }

    #search-input {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    #results {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <div id="results">
    <h2>Search Results:</h2>
    <?php
    // Example data - Replace this with your actual data source
    $data = [
      "apple", "banana", "orange", "grape", "watermelon", "pineapple"
    ];

    // Get the search term from the input field
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    $searchTerm = htmlspecialchars($searchTerm); // Sanitize input

    // Perform the search
    if ($searchTerm) {
      $foundItems = [];
      foreach ($data as $item) {
        if (strpos($item, $searchTerm) !== false) {
          $foundItems[] = $item;
        }
      }

      // Display the results
      if (count($foundItems)) {
        echo "<ul>";
        foreach ($foundItems as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for: " . $searchTerm . "</p>";
      }
    } else {
      echo "<p>Enter a search term.</p>";
    }
    ?>
