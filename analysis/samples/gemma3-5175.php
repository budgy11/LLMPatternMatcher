
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
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Ensures padding and border are included in the element's total width */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50; /* Green */
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #3e8e41;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("results");

      // Clear previous results
      resultsDiv.innerHTML = "";

      if (searchTerm.trim() === "") {
        return; // Don't do anything if the search term is empty
      }

      // Example search logic (replace with your actual search logic)
      var data = [
        { title: "Apple Pie", description: "A delicious apple pie recipe." },
        { title: "Banana Bread", description: "Homemade banana bread recipe." },
        { title: "Chocolate Cake", description: "Rich and decadent chocolate cake." },
        { title: "Strawberry Shortcake", description: "Classic strawberry shortcake recipe." }
      ];

      data.forEach(function(item) {
        if (item.title.toLowerCase().includes(searchTerm.toLowerCase())) {
          var listItem = document.createElement("div");
          listItem.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
          resultsDiv.appendChild(listItem);
        }
      });
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin-bottom: 10px;
    }

    .search-input {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" id="searchInput" placeholder="Search...">
  </div>

  <?php
  // Example data (replace with your actual data source)
  $items = [
    ['id' => 1, 'name' => 'Apple'],
    ['id' => 2, 'name' => 'Banana'],
    ['id' => 3, 'name' => 'Orange'],
    ['id' => 4, 'name' => 'Grape'],
  ];

  if (isset($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']); // Sanitize input

    // Perform the search
    $searchResults = [];
    foreach ($items as $item) {
      $itemTitle = strtolower($item['name']); // Case-insensitive search
      $searchTermLower = strtolower($searchTerm);

      if (strpos($itemTitle, $searchTermLower) !== false) {
        $searchResults[] = $item;
      }
    }
  }
  ?>
