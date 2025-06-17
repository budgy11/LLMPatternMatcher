

<!DOCTYPE html>
<html>
<head>
<title>Search Bar Example</title>
<style>
  .search-container {
    margin: 20px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 300px;
  }

  input[type="text"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Important for consistent sizing */
  }

  button {
    padding: 8px 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }

  button:hover {
    background-color: #3e8e41;
  }
</style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <?php
    // Example data (replace with your actual data source)
    $items = [
      "Apple",
      "Banana",
      "Orange",
      "Grape",
      "Strawberry"
    ];

    if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
      $searchTerm = $_GET['search_term'];

      echo "<h2>Search Results for: " . htmlspecialchars($searchTerm) . "</h2>";
      echo "<ul>";

      foreach ($items as $item) {
        if (strpos($item, $searchTerm) !== false) { // Case-sensitive search
          echo "<li>" . htmlspecialchars($item) . "</li>";
        }
      }

      echo "</ul>";

    } else {
      echo "<p>Enter a search term above.</p>";
    }
  ?>
