
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    /* Basic styling for the search bar */
    .search-container {
      width: 300px;
      margin: 20px auto;
      text-align: center;
    }

    .search-input {
      width: 80%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <div class="search-container">
    <input type="text" class="search-input" id="searchInput" placeholder="Search...">
    <button class="search-button" onclick="search()">Search</button>
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchInput").value;

      // Perform your search logic here.
      // This is just a placeholder example.

      //  Example:  Filtering a list of items
      var items = ["apple", "banana", "orange", "grape", "kiwi"];
      var results = items.filter(function(item) {
        return item.toLowerCase().includes(searchTerm.toLowerCase());
      });


      // Display the results (Replace this with your actual result handling)
      var resultsString = "Search Results: " + results.join(", ");
      alert(resultsString); // For simplicity, show the results in an alert
      //  Or, more elegantly, update the content of an element on the page
      //  e.g., document.getElementById("searchResults").innerHTML = results.join("<br>");

    }
  </script>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form input
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security - prevents SQL injection)
$searchTerm = htmlspecialchars($searchTerm);

// Escape the search term for use in the SQL query
$searchTerm = $conn->real_escape_string($searchTerm);  // Use your database connection's escape function

// Example: Search in a table named 'products' with a column named 'name'
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; 

// Execute the query
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
  // Output the search results
  echo "<form method='get' style='margin: 0;'>
        <input type='text' name='search' style='width: 300px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;' value='" . $searchTerm . "' placeholder='Search...' >
        <button type='submit' style='padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;'>Search</button>
      </form>";

  echo "<table border='1'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <!-- Add other columns as needed -->
          </tr>
        </thead>
        <tbody>";

  // Output the results
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
          <td>" . $row['id'] . "</td>
          <td>" . $row['name'] . "</td>
          <td>" . $row['description'] . "</td>
          <!-- Add other columns as needed -->
        </tr>";
  }

  echo "</tbody>
      </table>";

} else {
  echo "<p>No results found for '" . $searchTerm . "'.</p>";
}


// **Important Considerations and Improvements:**

// 1. **Database Connection:**  This code assumes you have a database connection established and assigned to the `$conn` variable.  You'll need to replace this placeholder with your actual database connection code.

// 2. **Security (SQL Injection):**  The most critical part is escaping the `$searchTerm` before using it in the SQL query.  *Never* directly concatenate user input into a SQL query. Use the proper escaping function provided by your database driver (e.g., `$conn->real_escape_string()` in this case, assuming you're using MySQLi).

// 3. **HTML Form:**
//   - The code creates a simple HTML form with an input field (`search`) and a submit button.
//   - `name='search'` is essential for the form data to be accessible via `$_GET['search']`.
//   - `value='` . $searchTerm . `'` automatically populates the input field with the search term, making it easier for the user to re-enter the search.
//   - `placeholder='Search...'` provides a helpful hint to the user.
//   - The styling is basic, but you can customize it further.

// 4. **Search Logic:**
//   - `LIKE '%` . $searchTerm . `%'` performs a partial string match.  This means that it will find results where the search term is *part* of the name, regardless of its position.
//   - The `%` characters are wildcards.  `%` matches zero or more characters, and `_` matches a single character.

// 5. **Error Handling:** Add error handling to check if the query was executed successfully and handle any errors.

// 6. **Performance:** For large datasets, consider using indexes on the database columns you're searching, and using more efficient search methods (e.g., full-text search).  The `LIKE` operator can be slow on large tables.

// 7. **Alternative Search Methods:**
//    - **Full-Text Search:** If you need more powerful and faster searching, consider implementing full-text search capabilities. Many database systems have built-in support for this.
//    - **Other Search Libraries:** If you're using a framework (e.g., Laravel, Symfony), they likely have built-in search functionality that you can utilize.


// Example Database Connection (MySQLi) - Replace with your actual connection details
/*
$conn = new mysqli("localhost", "your_username", "your_password", "your_database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/
?>
