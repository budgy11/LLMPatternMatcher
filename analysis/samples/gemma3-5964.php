  <button type="submit">Search</button>
</form>


<?php

//... (Database connection - $db)

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize (still recommended, but less critical with prepared statements)
$searchTerm = htmlspecialchars(trim($searchTerm));

if (empty($searchTerm)) {
    echo "<p>Please enter a search term.</p>";
} else {
    // Prepare the statement
    $sql = "SELECT * FROM your_table_name WHERE your_column_name LIKE ?";
    $stmt = mysqli_prepare($db, $sql);  // Use mysqli_prepare

    if ($stmt) {
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "s", $searchTerm); // "s" indicates a string

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
          //... (Display the search results - same as before)
        } else {
          echo "<p>Error executing query.</p>";
        }
        mysqli_stmt_close($stmt); // Close the statement
    } else {
        echo "<p>Error preparing statement.</p>";
    }
}
?>
