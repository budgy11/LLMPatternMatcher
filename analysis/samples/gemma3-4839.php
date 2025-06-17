    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

</body>
</html>


     // Using mysqli_stmt (Prepared Statements)
     $stmt = mysqli_prepare($conn, "SELECT * FROM your_table WHERE your_column LIKE ?");
     mysqli_stmt_bind_param($stmt, "s", $searchTerm);  // "s" indicates a string
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt);

     // Important:  Always close the statement
     mysqli_stmt_close($stmt);
     

     -  Prepared statements separate the query structure from the data, preventing malicious code from being injected into the query.
     -  `mysqli_stmt_bind_param()`:  This function binds the `$searchTerm` to the query, and the database driver handles escaping the data automatically.
     - `mysqli_stmt_get_result()`:  Gets the result set from the prepared statement.
     - `mysqli_stmt_close()`:  Always close the statement to release resources.

4. **Database Setup:**

   -  You'll need a database connection established (e.g., using `mysqli_connect()` or `PDO`).  This example assumes you have a `$conn` variable that's already established and contains your database connection object.  Make sure the table `your_table` exists and has a column named `your_column`.

**How to Use:**

1. **Replace Placeholders:** Update the code with your actual database credentials, table name, column name, and the appropriate database connection setup.
2. **Adapt the Output:** Modify the HTML table output to match the structure of your table's columns.
3. **Test Thoroughly:** Test the search with various search terms, including those with special characters, to ensure it's working correctly and securely.
4. **Implement Prepared Statements:**  *Always* use prepared statements to prevent SQL injection.

**Example Database Table (MySQL):**



<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      margin: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }

    input[type="text"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box; /* Important for consistent sizing */
    }

    button {
      padding: 8px 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
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

  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // You can add your search logic here.  This is just an example.
      // This example simply displays the search term in the console.
      console.log("Search term: " + searchTerm);

      // Example:  You could then use 'searchTerm' to query a database
      // or perform other search operations.

      // Reset the search box (optional)
      document.getElementById("searchInput").value = "";
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established ($conn)

function searchDatabase($searchTerm) {
  $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
  $result = mysqli_query($conn, $sql);

  $products = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }

  return $products;
}

// Example usage (in the search() function):
function search() {
    $searchTerm = document.getElementById("searchInput").value;
    $searchResults = searchDatabase($searchTerm);

    // Display results (example)
    let resultsDiv = document.getElementById("results");
    resultsDiv.innerHTML = ""; // Clear previous results

    for (let i = 0; i < $searchResults.length; i++) {
        let product = $searchResults[i];
        let productDiv = document.createElement("div");
        productDiv.appendChild(productDiv);
        productDiv.innerHTML = "Name: " + product.name + "<br>Description: " + product.description;
    }
}
?>
