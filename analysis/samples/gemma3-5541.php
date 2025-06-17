  <input type="text" name="search_term" placeholder="Enter search term..." value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">
  <input type="submit" value="Search">
</form>

<?php

//  Example of how to handle the search term and process the data
if (isset($_GET['search_term'])) {
  $search_term = htmlspecialchars($_GET['search_term']);

  // *** IMPORTANT SECURITY CONSIDERATIONS ***
  // 1.  Input Validation and Sanitization:  This example shows basic sanitization.  
  //     You absolutely MUST add robust input validation and sanitization. 
  //     This is the most critical part of any search bar to prevent security vulnerabilities
  //     like SQL injection or cross-site scripting (XSS).  Use appropriate functions 
  //     like `filter_var()` or a dedicated sanitization library.

  // 2.  Escape for Different Output Contexts:  `htmlspecialchars()` is great for HTML output,
  //     but you might need to escape the search term differently depending on where you're 
  //     using it (e.g., in a database query).

  //  Example:  Let's assume you're searching through a database table called 'products'
  //          that has a 'name' column.

  //  ***  WARNING:  This is a VERY simplified example and MUST be adapted to your specific database and needs. ***
  //  It's vulnerable to SQL injection if not properly handled.

  //  $query = "SELECT * FROM products WHERE name LIKE '%" . $query . "%'";  //  This is dangerous.
  //  $result = mysqli_query($connection, $query); // Replace $connection with your database connection

  //  If you are using prepared statements (HIGHLY recommended for security):

  //  $query = "SELECT * FROM products WHERE name LIKE ?";
  //  $stmt = mysqli_prepare($connection, $query); // Replace $connection with your database connection
  //  mysqli_stmt_bind_param($stmt, "s", $query); // "s" for string
  //  mysqli_stmt_execute($stmt);
  //  $results = mysqli_stmt_get_result($stmt);
  //  // ... Process the $results ...


  // Example of simply displaying the search term (for demonstration)
  echo "<p>You searched for: " . htmlspecialchars($search_term) . "</p>";

}
?>
