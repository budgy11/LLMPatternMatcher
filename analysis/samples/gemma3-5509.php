

<!DOCTYPE html>
<html>
<head>
  <title>Search Example</title>
  <style>
    .search-container {
      margin-bottom: 20px;
    }

    input[type="text"] {
      width: 300px;
      padding: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
      background-color: #4CAF50; /* Green */
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
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>


  <div id="searchResults">
    <!-- Results will be displayed here -->
  </div>



  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;
      var searchResultsDiv = document.getElementById("searchResults");

      // Clear previous results
      searchResultsDiv.innerHTML = "";

      // Example Data (Replace with your actual data source)
      var data = [
        { id: 1, name: "Apple", description: "A delicious fruit" },
        { id: 2, name: "Banana", description: "Another great fruit" },
        { id: 3, name: "Orange", description: "A citrus fruit" },
        { id: 4, name: "Grape", description: "Small and sweet" }
      ];

      // Perform the search
      if (searchTerm.trim() === "") {
        // Do nothing if the search term is empty
        return;
      }

      var foundItems = data.filter(function(item) {
        return item.name.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1; //Case-insensitive search
      });

      // Display the results
      if (foundItems.length > 0) {
        foundItems.forEach(function(item) {
          var listItem = document.createElement("li");
          listItem.textContent = item.name + " - " + item.description;
          searchResultsDiv.appendChild(listItem);
        });
      } else {
        var noResultsMessage = document.createElement("p");
        noResultsMessage.textContent = "No results found for '" + searchTerm + "'.";
        searchResultsDiv.appendChild(noResultsMessage);
      }
    }
  </script>

</body>
</html>


<?php

// Assuming you have a form to capture the search term
// Example HTML form:
// <form action="search.php" method="GET">
//   <input type="text" name="search_term" placeholder="Search...">
//   <input type="submit" value="Search">
// </form>

// PHP code to handle the search:

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $search_term = isset($_GET["search_term"]) ? trim($_GET["search_term"]) : "";

  // Perform your search logic here.
  // This is just a placeholder.  Replace this with your actual search code.

  if ($search_term != "") {
    // Example: Case-insensitive search through an array of items
    $items = [
      "apple",
      "banana",
      "orange",
      "grape",
      "pineapple"
    ];

    $results = [];
    foreach ($items as $item) {
      if (stripos($item, $search_term) !== false) {  // Case-insensitive
        $results[] = $item;
      }
    }

    if (empty($results)) {
      echo "<p>No results found for: " . htmlspecialchars($search_term) . "</p>";
    } else {
      echo "<ul>";
      foreach ($results as $result) {
        echo "<li>" . htmlspecialchars($result) . "</li>";
      }
      echo "</ul>";
    }
  }
  // If no search term is provided, you can display a default message
  else {
    echo "<p>Enter a search term.</p>";
  }

}

?>
