

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
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="performSearch()">Search</button>
  </div>

  <div id="results-container">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function performSearch() {
      var searchTerm = document.getElementById("search-input").value;
      var resultsContainer = document.getElementById("results-container");

      // Clear previous results
      resultsContainer.innerHTML = "";

      // Example data (replace with your actual data source)
      var data = [
        { title: "Apple iPhone 14", description: "A great phone!" },
        { title: "Samsung Galaxy S23", description: "Another excellent phone." },
        { title: "Google Pixel 7", description: "Powerful and versatile." },
        { title: "Laptop Example", description: "A powerful laptop." }
      ];

      // Filter data based on search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm.toLowerCase()) || item.description.toLowerCase().includes(searchTerm.toLowerCase());
      });

      // Display the filtered data
      if (filteredData.length > 0) {
        filteredData.forEach(function(item) {
          var listItem = document.createElement("div");
          listItem.innerHTML = `<strong>${item.title}</strong><br>${item.description}`;
          resultsContainer.appendChild(listItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.textContent = "No results found.";
        resultsContainer.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., using MySQLi or PDO)
// and a table named 'products' with a 'name' column.

// Example using MySQLi:
$db = new MySQLi("localhost", "your_db_username", "your_db_password", "your_db_name");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


// Get the search term from the form submission
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm); // Sanitize for security


// Query the database
$query = "SELECT id, name FROM products WHERE name LIKE '%" . $searchTerm . "%'";
$result = $db->query($query);

?>
