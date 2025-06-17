

<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Search...">
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    const searchInput = document.getElementById('search-input');
    const resultsDiv = document.getElementById('results');
    const searchTerm = ""; // Default search term

    searchInput.addEventListener('keyup', function(event) {
      const searchTerm = searchInput.value.toLowerCase();

      // Clear previous results
      resultsDiv.innerHTML = '';

      // Check if there's a search term
      if (searchTerm !== "") {
        // Example data (replace with your actual data source)
        const data = [
          { text: "Apple", id: 1 },
          { text: "Banana", id: 2 },
          { text: "Orange", id: 3 },
          { text: "Grape", id: 4 },
          { text: "Mango", id: 5 }
        ];

        // Filter the data based on the search term
        const filteredData = data.filter(item => item.text.toLowerCase().includes(searchTerm));

        // Display the filtered results
        if (filteredData.length > 0) {
          filteredData.forEach(item => {
            const listItem = document.createElement('li');
            listItem.textContent = item.text + " (ID: " + item.id + ")";
            resultsDiv.appendChild(listItem);
          });
        } else {
          // No results found
          resultsDiv.innerHTML = "<p>No results found.</p>";
        }
      }
    });
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      font-size: 16px;
    }
    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
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
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      //  Replace this with your actual search logic
      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear previous results
        return;
      }

      // Example:  Just displaying the search term
      resultsDiv.innerHTML = "<p>You searched for: " + searchTerm + "</p>";

      //  More complex search logic here, e.g.,
      //  - Fetch data from a database
      //  - Filter data based on the search term
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic Styling - Adjust as needed */
    .search-container {
      margin: 20px;
    }

    input[type="text"] {
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box; /* Ensures padding doesn't affect width */
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
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term">
    <button onclick="searchItems()">Search</button>
  </div>

  <script>
    function searchItems() {
      var searchTerm = document.getElementById("searchInput").value;
      if (searchTerm.trim() === "") {
        // Do nothing if search term is empty
        return;
      }

      // **Important: Replace this with your actual search logic**
      // This example just logs the search term to the console.
      console.log("Searching for: " + searchTerm);

      // **Example:  Search through an array**
      var items = ["Apple", "Banana", "Orange", "Grape", "Strawberry"];
      var results = items.filter(function(item) {
        return item.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display results in a dropdown or elsewhere.
      if (results.length > 0) {
        alert("Search Results:
" + results.join("
"));
      } else {
        alert("No results found for: " + searchTerm);
      }
    }
  </script>

</body>
</html>


<?php
  $searchTerm = $_GET['search']; // Get search term from URL
  $escapedSearchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); //Escape the search term

  // Now you can safely use $escapedSearchTerm in your database query
  // Example (replace with your actual query)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $escapedSearchTerm . "%'";
?>
