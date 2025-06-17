  <input type="submit" value="Search">
</form>

</body>
</html>


<?php

// Assume $db is your established database connection

// Get the search term
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Prepare the statement
$stmt = mysqli_prepare($db, "SELECT * FROM products WHERE name LIKE ?");

// Bind the parameter
mysqli_stmt_bind_param($stmt, "s", $searchTerm); // "s" indicates a string

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Display the results
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '" . $searchTerm . "'.</p>";
}

// Free the result set and close the statement
mysqli_free_result($result);
mysqli_stmt_close($stmt);

?>
