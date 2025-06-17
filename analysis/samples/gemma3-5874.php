

   echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
   

   This converts special characters (like `<` and `>`) into their HTML entities, preventing them from being interpreted as HTML tags.  The `ENT_QUOTES` flag ensures that both single and double quotes are escaped.
* **Parameterization:** When querying a database, *always* use parameterized queries to prevent SQL injection.  Do not concatenate user input directly into your SQL queries.

Example with `htmlspecialchars()`:



<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin: 20px;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
    }

    input[type="text"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 16px;
    }

    button {
      padding: 8px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
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

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // **Important:** Replace this with your actual search logic.
      // This is just a placeholder for demonstration.

      if (searchTerm.trim() === "") {
        return; // Do nothing if the search term is empty
      }

      // **Example search logic (replace with your data source):**
      var results = performSearch(searchTerm);

      // Display the results (replace with your desired display method)
      displaySearchResults(results);
    }

    // Placeholder function for your search logic
    function performSearch(searchTerm) {
      // This is just a placeholder. Replace with your database query,
      // API call, or whatever you need to perform the search.
      // For example:
      //   - Query a database:  mysqli_query(...);
      //   - Make an API request:  fetch(...);

      // Mock data for testing:
      var results = [
        "Result 1 for " + searchTerm,
        "Result 2 for " + searchTerm,
        "Result 3 for " + searchTerm
      ];
      return results;
    }


    function displaySearchResults(results) {
      // This function displays the search results.
      // Replace this with your desired way to display the results,
      // such as:
      // - Creating an HTML list:  <ul></ul>
      // - Updating the contents of a specific element in the page.

      var resultsDiv = document.getElementById("results");
      if (!resultsDiv) {
          resultsDiv = document.createElement("div");
          resultsDiv.id = "results";
          document.body.appendChild(resultsDiv); // or insert before an existing element
      }

      resultsDiv.innerHTML = ""; // Clear previous results

      for (var i = 0; i < results.length; i++) {
        var resultItem = document.createElement("p");
        resultItem.textContent = results[i];
        resultsDiv.appendChild(resultItem);
      }
    }
  </script>

</body>
</html>


<?php
  // Assuming you have a database connection established (e.g., $conn)

  function performSearch($searchTerm) {
    $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($conn, $query);

    $products = array();
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row['name'] . ' - ' . $row['description'];
      }
    }

    mysqli_free_result($result);
    return $products;
  }
?>
