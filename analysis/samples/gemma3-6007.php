

   $stmt = mysqli_prepare($db, "SELECT * FROM products WHERE name LIKE ?"); // Use a placeholder (?)
   mysqli_stmt_bind_string($stmt, 1, $searchTerm);  // Bind the search term
   mysqli_stmt_execute($stmt);
   mysqli_stmt_bind_result($stmt, $id, $name, $description);
   $result = mysqli_fetch_assoc($stmt);

   mysqli_stmt_close($stmt);
   

2. **Full-Text Search:**  For larger datasets or more complex searches, consider using full-text search capabilities provided by your database system (e.g., MySQL's `MATCH...AGAINST` or other full-text indexes). These are *significantly* faster and more efficient than using `LIKE` for many scenarios.

3. **Database Indexing:**  Make sure you have an index on the column you're searching (e.g., `name` in this example). This dramatically speeds up the search.

4. **Search Term Handling:**  You might want to add features like:
   - **Case-insensitive search:** Use `strtolower()` or `ucfirst()` to convert both the search term and the column to the same case before comparing.
   - **Limit the number of results:** Use `LIMIT` in your SQL query to return only a certain number of results.
   - **Pagination:** If you have a very large number of results, implement pagination to display them in smaller chunks.

5. **User Experience:**
   - Provide clear feedback to the user (e.g., a "Searching..." message while the query is running).
   - Handle errors gracefully and display informative messages to the user.

6. **Database Choice:** Consider using a more robust database system (like PostgreSQL, MySQL, or MariaDB) for production environments.

**Example using Prepared Statements (Recommended):**



<?php

// Assuming you're using a simple array of data for demonstration
$data = [
  "apple", "banana", "orange", "grape", "watermelon"
];

// Function to handle the search bar
function searchData($searchTerm, $data) {
  // Convert search term to lowercase for case-insensitive search
  $searchTerm = strtolower($searchTerm);

  // Filter the data array using array_filter
  $results = array_filter($data, function ($item) use ($searchTerm) {
    // Convert the data item to lowercase for case-insensitive comparison
    return strtolower($item) === $searchTerm;
  });

  // Return the filtered array
  return $results;
}

// Get the search term from the form (example)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : ''; // Get from GET request

// Perform the search
$searchResults = searchData($searchTerm, $data);

?>
