    <input type="text" name="search_term" placeholder="Enter product name">
    <button type="submit">Search</button>
  </form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    #search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    #search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    #search-button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <?php
    // Example data (replace with your actual data source)
    $data = [
      ["name" => "Apple", "description" => "A red fruit"],
      ["name" => "Banana", "description" => "A yellow fruit"],
      ["name" => "Orange", "description" => "A citrus fruit"],
      ["name" => "Grape", "description" => "Small, juicy fruit"]
    ];

    // Handle the search button click
    if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
      $searchTerm = $_GET['search_term'];

      // Perform the search
      $results = [];
      foreach ($data as $item) {
        // Case-insensitive search
        $name = strtolower($item['name']);
        $description = strtolower($item['description']);
        $searchTermLower = strtolower($searchTerm);

        if (strpos($name, $searchTermLower) !== false || strpos($description, $searchTermLower) !== false) {
          $results[] = $item;
        }
      }

      // Display the search results
      if (!empty($results)) {
        echo "<h2>Search Results:</h2>";
        echo "<ul>";
        foreach ($results as $result) {
          echo "<li>Name: " . $result['name'] . ", Description: " . $result['description'] . "</li>";
        }
        echo "</ul>";
      } else {
        echo "<p>No results found for '" . $searchTerm . "'.</p>";
      }
    }
  ?>
