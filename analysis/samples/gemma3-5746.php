

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px; /* Adjust width as needed */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
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

      // Clear previous results (optional)
      resultsDiv.innerHTML = "";

      // Perform your search logic here.
      // This is a placeholder example. Replace with your actual data source and search algorithm.
      if (searchTerm.trim() === "") {
        // Handle empty search term - display a message or do nothing
        resultsDiv.innerHTML = "<p>Please enter a search term.</p>";
        return;
      }

      // Example: Simple search through an array of items
      var items = ["apple", "banana", "orange", "grape", "watermelon"];
      var foundItems = items.filter(function(item) {
        return item.toLowerCase().includes(searchTerm.toLowerCase());
      });

      if (foundItems.length > 0) {
        var resultsHTML = "<ul>";
        foundItems.forEach(function(item) {
          resultsHTML += "<li>" + item + "</li>";
        });
        resultsHTML += "</ul>";
        resultsDiv.innerHTML = resultsHTML;
      } else {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a form to collect the search term
?>
