

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
      box-sizing: border-box;
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
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="search()" size="30">
    <button onclick="search()">Search</button>
  </div>

  <div id="results">
    <!-- Results will be displayed here -->
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");
      resultsDiv.innerHTML = ""; // Clear previous results

      // Example data (replace with your actual data source)
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry", "Pineapple", "Mango"
      ];

      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm) > -1) {
          var listItem = document.createElement("p");
          listItem.textContent = data[i];
          resultsDiv.appendChild(listItem);
        }
      }
    }
  </script>

</body>
</html>


<?php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the search term from the form
  $searchTerm = $_POST["search_term"];

  // Sanitize the search term (important for security)
  $searchTerm = htmlspecialchars(trim($searchTerm));

  // Basic validation - you can add more complex validation here
  if (empty($searchTerm)) {
    $message = "Please enter a search term.";
  } else {
    // Perform your search logic here.
    // This is just an example; you'll replace it with your database query,
    // array search, or other search method.
    $results = search($searchTerm); // Call a function to perform the search

    // Display the results
    if (is_array($results)) {
      echo "<p>Search results for: <strong>" . $searchTerm . "</strong></p>";
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . $result . "</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>No results found for: <strong>" . $searchTerm . "</strong></p>";
    }
  }
}
?>
