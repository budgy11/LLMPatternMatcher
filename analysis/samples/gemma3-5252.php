
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
</head>
<body>

  <h1>Search Products</h1>

  <form method="GET" action="">
    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      margin-bottom: 10px;
    }

    #search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 8px 15px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
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
    // Example Data (Replace with your actual data source)
    $data = [
      ["item1", "description1"],
      ["item2", "description2"],
      ["item3", "description3"],
      ["item4", "description4"],
      ["item5", "description5"]
    ];

    // Get the search term from the input field
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Perform the search
    $searchResults = [];
    foreach ($data as $item) {
      if (strpos($item[0], $searchTerm) !== false) { //Case-sensitive search
          $searchResults[] = $item;
      }
    }

    // Display the results
    if (empty($searchResults)) {
      echo "<p>No results found.</p>";
    } else {
      echo "<ul>";
      foreach ($searchResults as $result) {
        echo "<li>" . $result[0] . " - " . $result[1] . "</li>";
      }
      echo "</ul>";
    }
    ?>
