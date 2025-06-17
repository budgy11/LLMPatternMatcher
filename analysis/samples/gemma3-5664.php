
</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Simple Search Bar</title>
  <style>
    .search-container {
      margin-bottom: 10px;
    }

    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 300px;
      box-sizing: border-box; /* Important for consistent sizing */
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

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = ""; // Clear results if input is empty
        return;
      }

      // **Replace this with your actual search logic**
      // This is just a placeholder that simulates searching.
      var data = [
        "Apple", "Banana", "Orange", "Grape", "Strawberry",
        "Cat", "Dog", "Bird", "Fish", "Elephant"
      ];

      var results = "";
      for (var i = 0; i < data.length; i++) {
        if (data[i].toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1) {
          results += "<p>" + data[i] + "</p>";
        }
      }

      if (results === "") {
        resultsDiv.innerHTML = "<p>No results found.</p>";
      } else {
        resultsDiv.innerHTML = results;
      }
    }
  </script>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
  <title>Search with Database</title>
  <style> /* Same CSS as above */ </style>
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

      if (searchTerm.trim() === "") {
        resultsDiv.innerHTML = "";
        return;
      }

      // **Replace with your actual database connection and query**
      // This is just a placeholder example.
      var searchTermLower = searchTerm.toLowerCase(); // Convert for case-insensitive comparison

      //  Example:  Assume a database connection and a 'products' table with a 'name' column
      //  Adjust this to match your database schema.
      $.ajax({
          url: 'get_products.php?search_term=' + searchTermLower, // Call a separate PHP file
          method: 'GET',
          success: function(data) {
              var results = "";
              if (data) { // Check if data is returned from the server
                for (var i = 0; i < data.length; i++) {
                  results += "<p>" + data[i].name + "</p>";
                }
              }
              if (results === "") {
                resultsDiv.innerHTML = "<p>No results found.</p>";
              } else {
                resultsDiv.innerHTML = results;
              }
          },
          error: function(xhr, status, error) {
            console.error("Error fetching products: " + error);
            resultsDiv.innerHTML = "<p>An error occurred.</p>";
          }
      });
    }
  </script>
</body>
</html>


<?php

// **Option 1: Simple Search - Basic String Matching**

// This option performs a basic string search through an array of data.
// It's suitable for small datasets.

function searchData($searchTerm, $dataArray) {
  $results = [];
  foreach ($dataArray as $item) {
    if (strpos($item, $searchTerm) !== false) {
      $results[] = $item;
    }
  }
  return $results;
}

// Example Usage:
$searchTerms = array("apple", "banana", "orange", "grapefruit");
$data = array(
  "red apple",
  "yellow banana",
  "orange juice",
  "grapefruit",
  "green apple"
);

$searchResults = searchData("apple", $data);
print_r($searchResults); // Output: Array ( [0] => red apple [1] => green apple )


// **Option 2: Search with Input Sanitization & More Robust Logic**

// This option includes sanitization to prevent basic SQL injection (though it's not a substitute for proper database security!)
// and more flexible searching (e.g., case-insensitive).
//  It's better for interactive user forms.

function searchDataSanitized($searchTerm, $dataArray, $caseSensitive = false) {
  $searchTerm = strtolower($searchTerm); // Convert to lowercase for case-insensitive searching.

  $results = [];
  foreach ($dataArray as $item) {
    $itemLower = strtolower($item);
    if ($caseSensitive === false || strpos($itemLower, $searchTerm) !== false) {
      $results[] = $item;
    }
  }
  return $results;
}

// Example Usage (Case-insensitive):
$searchTerms = array("apple", "banana", "orange", "grapefruit");
$data = array(
  "red apple",
  "yellow banana",
  "orange juice",
  "grapefruit",
  "green apple"
);

$searchResults = searchDataSanitized("apple", $data);
print_r($searchResults); // Output: Array ( [0] => red apple [1] => green apple )

$searchResultsCaseSensitive = searchDataSanitized("apple", $data, true);
print_r($searchResultsCaseSensitive);  // Output: Array ( [0] => red apple )



// **Option 3: Search with Database (Recommended for Larger Datasets)**

// This is the best approach for large datasets.  It assumes you have a database connection established.

// (This section requires a database connection and table setup)
// Assuming a database connection called $db

//  First, you'd need to create a table (e.g., 'products') with a column to search.
//  Example:  CREATE TABLE products (id INT PRIMARY KEY, name VARCHAR(255), description TEXT)

//  Then, the search query would look like this:

//  $searchTerm = $_GET['search'];  // Get search term from GET request
//  $db->query("SELECT * FROM products WHERE name LIKE '%" . $db->escapeString($searchTerm) . "%' OR description LIKE '%" . $db->escapeString($searchTerm) . "%'");
//  $results = $db->fetchAll();

//  Note: $db->escapeString() is *essential* to prevent SQL injection.

//  Example:
/*
$searchTerm = $_GET['search'];
$query = "SELECT * FROM products WHERE name LIKE '%" . $db->escapeString($searchTerm) . "%' OR description LIKE '%" . $db->escapeString($searchTerm) . "%'";
$result = $db->query($query);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $searchResults[] = $row;
  }
}

print_r($searchResults);
*/



// **Important Considerations & Best Practices**

// 1. **SQL Injection:**  *Always* use proper escaping functions (like $db->escapeString() if you're using a database) to prevent SQL injection vulnerabilities.  Never directly embed user input into SQL queries.

// 2. **Performance:**  For large datasets, use database indexes on the search columns to speed up queries.  Consider using full-text search capabilities offered by your database system.

// 3. **Case Sensitivity:**  Decide if your search should be case-sensitive or case-insensitive.  The examples above demonstrate case-insensitive searching using `strtolower()`.

// 4. **Partial Matching:** The `LIKE '%searchterm%'` syntax in the database examples allows for partial matching.  This is important for users who don't know the exact term.

// 5. **User Experience:**
//    * Implement auto-suggestions (using AJAX and a database) as the user types.
//    * Provide clear feedback to the user about the search results.
//    * If no results are found, display a helpful message.

// 6. **Frameworks/Libraries:**  In a real-world web application, consider using a framework (like Laravel, Symfony, CodeIgniter) that provides built-in search functionality.  These frameworks often handle much of the complexity for you.

// **To use this in your application:**

// 1. Choose the option that best fits your needs (simple string matching for small datasets, database search for larger ones).
// 2. Adapt the code to your specific data structure and database connection.
// 3.  Always prioritize security – especially when dealing with user input.


<?php

// Assuming you have a database connection established (e.g., $db_connection)

// Get the search term from the form
$search_term = isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : '';

// Perform the search
if ($search_term) {
  //  Example: Search through a database table named 'products'
  //  Replace this with your actual query and database setup.

  $results = [];

  // Example query (adapt to your database schema and needs)
  $sql = "SELECT * FROM products WHERE product_name LIKE '%" . $search_term . "%' OR description LIKE '%" . $search_term . "%'";
  $result = mysqli_query($db_connection, $sql);

  if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }
  }

  // You can add error handling here to check if the query was successful.
  mysqli_free_result($result); // Free up the result set

} else {
  //  No search term entered, display all results or a default page.
  //  You can adjust this part to show all products or a "No search term" page.
  $results = []; // Reset the results array
  //  Example: Display all products
  //  $sql = "SELECT * FROM products";
  //  $result = mysqli_query($db_connection, $sql);
  //  $results = [];
  //  while ($row = mysqli_fetch_assoc($result)) {
  //    $results[] = $row;
  //  }
  //  mysqli_free_result($result);
}


?>
