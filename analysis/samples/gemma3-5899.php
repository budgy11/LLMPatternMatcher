
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-bar {
      width: 300px;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-bottom: 10px;
    }

    #search-button {
      padding: 8px 15px;
      background-color: #4CAF50;
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

  <h2>Search</h2>

  <input type="text" id="search-bar" placeholder="Enter search term...">
  <button id="search-button">Search</button>

  <?php
    // Handle the search form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get the search term
      $searchTerm = $_POST["search_term"];

      // Sanitize and validate the search term (IMPORTANT!)
      $searchTerm = htmlspecialchars(trim($searchTerm)); // Remove HTML tags and whitespace

      if (empty($searchTerm)) {
        echo "<p>Please enter a search term.</p>";
      } else {
        // Example: Search through a simple array (replace with your actual data source)
        $myArray = array(
          "apple",
          "banana",
          "orange",
          "grape",
          "mango"
        );

        $searchResults = array();
        foreach ($myArray as $item) {
          if (stripos($item, $searchTerm) !== false) { // Case-insensitive search
            $searchResults[] = $item;
          }
        }

        if (empty($searchResults)) {
          echo "<p>No results found for: " . $searchTerm . "</p>";
        } else {
          echo "<h3>Search Results:</h3>";
          echo "<ul>";
          foreach ($searchResults as $result) {
            echo "<li>" . $result . "</li>";
          }
          echo "</ul>";
        }
      }
    }
  ?>
