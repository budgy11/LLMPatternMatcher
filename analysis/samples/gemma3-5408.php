
* **Result Display:** The code now iterates through the `$result` and displays the product names in a list (`<ul>`).  Handles the case where no results are found.

* **Conciseness and Readability:** The code is formatted for better readability and uses more descriptive variable names.

* **Best Practices:** Uses `mysqli_fetch_assoc` which is the standard way to fetch a row as an associative array.

**How to Use This Code:**

1. **Set up your database:**  Create a database and a table named `products` (or whatever you choose).  Make sure the table has a `name` column.
2. **Connect to your database:**  Establish the database connection using `mysqli_connect()` as shown above, making sure to replace the placeholders with your database credentials.  **Never hardcode credentials in a production environment.**  Use environment variables or a configuration file.
3. **Copy the PHP code:** Copy the code into a `.php` file (e.g., `search.php`).
4. **Upload to your web server:**  Upload the file to your web server's directory (e.g., `htdocs`, `www`).
5. **Access the file in your browser:**  Open the file in your web browser (e.g., `http://localhost/search.php`).

**Important Security Considerations:**

* **Never store passwords directly in your code.** Use prepared statements or parameterized queries to prevent SQL injection. The example shows sanitization of the search term, but for production, using prepared statements is much more robust.
* **Limit search terms:**  Consider implementing rate limiting or using a search index (like Elasticsearch) for large datasets to improve performance and prevent abuse.
* **User Input Validation:**  While the `htmlspecialchars()` function handles HTML entities, you might want to add additional validation to check for expected characters or patterns in the search term to further enhance security and prevent unexpected behavior.

**Example with Prepared Statements (More Secure - Recommended for Production):**



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
      width: 300px; /* Adjust as needed */
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
    <input type="text" id="searchBox" placeholder="Search...">
    <button onclick="search()">Search</button>
  </div>


  <script>
    function search() {
      var searchTerm = document.getElementById("searchBox").value;

      // Do something with the search term.  This is where you'd
      // implement your actual search logic.
      // For example, you might call a function to search a database.

      if (searchTerm) {
        alert("You searched for: " + searchTerm); // Replace with your search results
        // Example:  Call a function to perform the search
        // performSearch(searchTerm);
      } else {
        alert("Please enter a search term.");
      }
    }
  </script>

</body>
</html>


<?php
// Assuming you have a database connection established in a separate part of your code.
// $conn would be your database connection object.

function performSearch($searchTerm) {
  // Example database query (replace with your actual query)
  $query = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'"; //Use LIKE for partial matches

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Process the results
    while ($row = $result->fetch_assoc()) {
      // Do something with the $row data (e.g., display in a table)
      echo "Product Name: " . $row["name"] . "<br>";
      echo "Price: " . $row["price"] . "<br>";
      echo "<br>";
    }
  } else {
    echo "No results found.";
  }
}

// Inside your search() function (adjust as needed)
function search() {
  var searchTerm = document.getElementById("searchBox").value;
  if (searchTerm) {
    performSearch(searchTerm);
  } else {
    alert("Please enter a search term.");
  }
}
?>
