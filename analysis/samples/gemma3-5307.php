    <input type="text" name="search" value="<?php echo $search_term; ?>" placeholder="Enter product name or keyword">
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
      margin: 20px;
      width: 300px;
    }
    #search-input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box; /* Important for consistent sizing */
    }
    #search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    #search-results {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    #search-results li {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #eee;
    }
  </style>
</head>
<body>

  <div id="search-container">
    <input type="text" id="search-input" placeholder="Search...">
    <button id="search-button">Search</button>
  </div>

  <ul id="search-results">
    <!-- Search results will be added here dynamically -->
  </ul>

  <script>
    // Sample data (replace with your actual data source)
    const data = [
      { id: 1, title: "Product A", description: "This is product A." },
      { id: 2, title: "Product B", description: "This is product B." },
      { id: 3, title: "Product C", description: "This is product C." },
      { id: 4, title: "Another Item", description: "A different item." }
    ];

    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-button");
    const searchResults = document.getElementById("search-results");

    searchButton.addEventListener("click", function() {
      const searchTerm = searchInput.value.toLowerCase();  // Convert to lowercase for case-insensitive search
      const results = [];

      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.title.toLowerCase().includes(searchTerm) ||
            item.description.toLowerCase().includes(searchTerm)) {
          results.push(item);
        }
      }

      // Display the results
      searchResults.innerHTML = ""; // Clear previous results
      if (results.length > 0) {
        results.forEach(item => {
          const listItem = document.createElement("li");
          listItem.textContent = `${item.title} - ${item.description}`;
          searchResults.appendChild(listItem);
        });
      } else {
        searchResults.textContent = "No results found.";
      }
    });

    // Handle Enter key press as search
    searchInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            searchButton.click();
        }
    });
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
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
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

  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("searchResults");

      // Example Data (Replace with your actual data source)
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry", "Pineapple"
      ];

      // Filter the data based on the search term
      var filteredData = data.filter(function(item) {
        return item.toLowerCase().includes(searchTerm);
      });

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Display the filtered results
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var resultDiv = document.createElement("p");
          resultDiv.appendChild(resultDiv);
          resultDiv.textContent = item;
        });
      } else {
        resultDiv.textContent = "No results found.";
      }
    }
  </script>

</body>
</html>


<?php

// Sample Data (replace with your actual data source - e.g., database)
$items = [
    "apple",
    "banana",
    "orange",
    "grape",
    "watermelon",
    "pineapple",
    "strawberry"
];

// Function to handle the search
function searchItems($searchTerm, $items) {
    $results = [];

    // Convert search term to lowercase for case-insensitive search
    $searchTerm = strtolower($searchTerm);

    foreach ($items as $item) {
        // Convert item to lowercase for case-insensitive comparison
        $itemLower = strtolower($item);

        // Check if the search term is a substring of the item
        if (strpos($itemLower, $searchTerm) !== false) {
            $results[] = $item;
        }
    }

    return $results;
}

// Get the search term from the form (if submitted) or directly from the URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';  // Get from URL or empty if not provided

// Perform the search
$searchResults = searchItems($searchTerm, $items);

?>
