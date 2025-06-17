

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
      width: 90%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
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

  <div id="searchResults" style="margin-top: 20px;">
    <!-- Search results will appear here -->
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var resultsDiv = document.getElementById("searchResults");

      // **Important:** Replace this with your actual search logic
      // This is just a placeholder to demonstrate how to display results.
      // You should replace it with your database query or other search method.

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear results if search term is empty
        return;
      }


      // Example search logic (replace with your actual implementation)
      var results = [];
      if (searchTerm.toLowerCase() === "apple") {
          results = ["Apple iPhone", "Apple iPad", "Apple Watch"];
      } else if (searchTerm.toLowerCase() === "banana") {
          results = ["Banana Bunch", "Banana Bread"];
      }
      else {
          results = ["No results found", "Try a different search term"];
      }

      // Clear previous results
      resultsDiv.innerHTML = "";

      // Display the results
      if (results.length > 0) {
          for (var i = 0; i < results.length; i++) {
              var resultItem = document.createElement("p");
              resultItem.textContent = results[i];
              resultsDiv.appendChild(resultItem);
          }
      }
      else{
          var noResultItem = document.createElement("p");
          noResultItem.textContent = "No results found";
          resultsDiv.appendChild(noResultItem);
      }

    }
  </script>

</body>
</html>


<?php

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars(trim($_POST["search_term"]));

  // Validate the search term (optional, but recommended)
  if (empty($searchTerm)) {
    $searchResults = []; // Empty result set
  } else {
    // Perform your search logic here
    // Example: Search in a database or an array
    $searchResults = searchDatabase($searchTerm);
  }

} else {
  // Initialize the search term if the form hasn't been submitted
  $searchTerm = "";
  $searchResults = [];
}
?>
