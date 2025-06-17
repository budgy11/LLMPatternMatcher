
<!DOCTYPE html>
<html>
<head>
  <title>Search Bar Example</title>
  <style>
    /* Basic Styling - Customize as needed */
    .search-container {
      margin: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }

    input[type=\"text\"] {
      width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
  </style>
</head>
<body>

  <div class=\"search-container\">
    <h2>Search:</h2>
    <input type=\"text\" id=\"searchInput\" placeholder=\"Enter your search term...\">
    <button onclick=\"searchItems()\">Search</button>
  </div>

  <div id=\"searchResults\">
    <!-- Results will be displayed here -->
  </div>

  <script>
    function searchItems() {
      var searchTerm = document.getElementById('searchInput').value;
      var searchResultsDiv = document.getElementById('searchResults');

      searchResultsDiv.innerHTML = '';

      if (searchTerm.trim() === '') {
        return;
      }

      // Filter data based on the search term
      var filteredData = data.filter(function(item) {
        return item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
               item.description.toLowerCase().includes(searchTerm.toLowerCase());
      });

      if (filteredData.length > 0) {
        filteredData.forEach(function(item, index) {
          var resultDiv = document.createElement('div');
          resultDiv.innerHTML = '<strong>' + item.title + '</strong><br>' + item.description;
          resultDiv.style.marginBottom = '10px';
          resultDiv.onclick = function() {
            alert('Clicked on: ' + item.title);
          };
          searchResultsDiv.appendChild(resultDiv);
        });
      } else {
        var noResultsDiv = document.createElement('div');
        noResultsDiv.innerHTML = '<p>No results found for \'' + searchTerm + '\'</p>';
        searchResultsDiv.appendChild(noResultsDiv);
      }
    }

    document.getElementById('searchInput').addEventListener('keyup', function(event) {
      searchItems();
    });
</script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db connection)
// and you're using a database that supports SQL LIKE operator

// Get the search term from the request
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (VERY IMPORTANT to prevent SQL injection)
//  - Escape single quotes ('), double quotes ("), and backslashes (\)
//  - Consider using prepared statements for added security
$safe_search_term = $db->real_escape_string($search_term);  // Assuming $db is your database object

// Build the SQL query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $safe_search_term . "%'";
// Replace 'your_table_name' and 'your_column_name' with your actual table and column names
// The '%' wildcard allows partial matches

// Execute the query
$result = $db->query($query);

// Check if the query was successful
if ($result) {
    // Display the results
    echo "<form action=\"search.php\" method=\"get\">
            <input type=\"text\" name=\"search\" placeholder=\"Search...\" value=\"" . htmlspecialchars($search_term) . "\">
            <button type=\"submit\">Search</button>
          </form>";

    echo "<br>";
    echo "<table border=\"1\">
            <thead>
              <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>...</th>
              </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $column_name => $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";  //Escape output to prevent XSS
        }
        echo "</tr>";
    }

    echo "</tbody>
          </table>";

} else {
    echo "No results found for '" . htmlspecialchars($search_term) . "'."; //Escape output
}

?>
