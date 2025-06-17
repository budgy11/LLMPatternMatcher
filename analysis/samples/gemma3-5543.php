     

   * **Searching a database:**  You would use a database query (e.g., `mysqli_query()` or `PDO`) to search your database tables.

   * **AJAX Search (Recommended for Better User Experience):**  Use JavaScript to send the search term to the server *without* reloading the entire page.  This provides a much smoother user experience.

   * **Using `$_GET`:**  The example above gets the search term from the URL using `$_GET["search"]`.  This is a simple way to pass the search term from the user's browser.  However, for security reasons, you should *always* validate and sanitize the input before using it in a search query.

4. **Security and Sanitization:**  *Crucially important!*  Always sanitize and validate user input to prevent security vulnerabilities (like SQL injection).  For database queries, use prepared statements or parameterized queries to avoid SQL injection.   Also, use input validation to ensure the search term is in a reasonable format.

Example of sanitization (using `mysqli` - adapt for your database):



<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the search bar
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (important for security)
$searchTerm = htmlspecialchars(trim($searchTerm));

// Build the SQL query
$query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";

// Execute the query
$result = mysqli_query($db, $query);

// Check for errors
if (mysqli_error($db)) {
    echo "<p style='color:red;'>Error: " . mysqli_error($db) . "</p>";
}

// Display the search results
echo "<form method='GET' action='search_results.php'>
       <input type='text' name='search' value='" . $searchTerm . "' placeholder='Search...'>
       <button type='submit'>Search</button>
     </form>

     <h2>Search Results:</h2>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        // Display the data from the row
        echo "<p>" . $row['your_column_name_1'] . ": " . $row['your_column_name_2'] . "</p>"; // Replace with your actual column names
        echo "</div>";
    }
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// Close the database connection (important!)
mysqli_close($db);

?>
