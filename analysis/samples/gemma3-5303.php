

<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    .search-container {
      margin: 20px;
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
    <h2>Search:</h2>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <button onclick="search()">Search</button>
  </div>

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      //  Validate that the search term isn't empty
      if (searchTerm.trim() === "") {
        alert("Please enter a search term.");
        return; // Exit the function
      }

      //  Here you would typically implement your search logic.
      //  This example just logs the search term to the console.

      console.log("Searching for: " + searchTerm);

      //  Replace this with your actual search implementation.
      //  This could involve:
      //  1.  Filtering data from a database.
      //  2.  Using a third-party search API.
      //  3.  Displaying search results on the page.

      // Example: Displaying results (replace with your actual data source)
      // let results = filterData(searchTerm);
      // displayResults(results);

    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established
// (using PHP's mysqli or PDO)

if (isset($_SERVER["REQUEST_METHOD"]) && "POST" == $_SERVER["REQUEST_METHOD"]) {
  $searchTerm = $_POST["search_term"];

  //  Escape the search term to prevent SQL injection
  $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

  //  Construct your SQL query (example - adjust to your table/field names)
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";

  // Execute the query
  $result = mysqli_query($conn, $sql);

  // Process the results (e.g., display them on the page)
  // ...  (Displaying the results would go here)
}
?>
