    <button onclick="searchFunction()">Search</button>
  </div>

  <div id="results">
    <!-- Search results will be displayed here -->
  </div>

  <script>
    function searchFunction() {
      var searchTerm = document.getElementById("searchInput").value.toLowerCase();
      var resultsDiv = document.getElementById("results");

      resultsDiv.innerHTML = "";

      // Now use the search term
      var results = [];
      for (var i = 0; i < data.length; i++) {
          if (data[i].toLowerCase().includes(searchTerm)) {
              var resultItem = document.createElement("p");
              resultItem.textContent = data[i];
              resultsDiv.appendChild(resultItem);
          }
      }

    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// and you're searching within a table called 'products' with a column named 'name'

// 1. Get the Search Term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security!)
$search_term = htmlspecialchars(trim($search_term));

// 2. Build the SQL Query
$query = "SELECT * FROM products WHERE name LIKE '%" . $search_term . "%'";

// 3. Execute the Query
// IMPORTANT:  Use prepared statements to prevent SQL injection!
//  This example uses a simple example, but in a real application, you *must* use prepared statements.
//  This is just for illustrative purposes.

// Example with a prepared statement (recommended):
// $stmt = $db->prepare("SELECT * FROM products WHERE name LIKE ?");
// $stmt->bind_param("s", "%" . $search_term . "%");
// $stmt->execute();
// $results = $stmt->get_result();


// 4. Display the Results
$results = $db->query($query); // Replace $db with your database connection object

if ($results->num_rows > 0) {
    echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th></tr>";

    while ($row = $results->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

} else {
    echo "<p>No products found matching your search.</p>";
}

?>
