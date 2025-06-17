
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      width: 80%;
      margin: 20px auto;
    }

    #search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    #search-results {
      margin-top: 20px;
      border: 1px solid #ccc;
      padding: 10px;
    }

    .result-item {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <div id="search-results">
    <h2>Search Results:</h2>
    <?php
    // Placeholder data - replace with your database query
    $data = [
      "apple", "banana", "orange", "grape", "kiwi", "mango",
      "peach", "pear", "plum", "strawberry", "watermelon"
    ];

    if (isset($_GET['search_term'])) {
      $searchTerm = $_GET['search_term'];

      $searchResults = [];

      foreach ($data as $item) {
        if (strpos($item, $searchTerm) !== false) {
          $searchResults[] = $item;
        }
      }

      if (empty($searchResults)) {
        echo "<p>No results found.</p>";
      } else {
        echo "<ul>";
        foreach ($searchResults as $result) {
          echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
      }
    }
    ?>
