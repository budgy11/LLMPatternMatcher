
</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search query from the form
$search_query = $_GET['search'];

// Sanitize the input - VERY IMPORTANT!  This prevents SQL injection.
// Escape the search term to prevent malicious code execution.
$search_query = $conn->real_escape_string($search_query); 

//  Handle empty search query (optional - depending on your desired behavior)
if(empty($search_query)) {
    $results = []; // Or redirect to a default page
} else {

    //  Example query - adjust based on your database structure and needs.
    //  This example searches the 'products' table for a 'name' field.
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $search_query . "%'"; 

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output the results
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>" . $row["name"] . " - " . $row["description"] . "</li>";  // Adjust based on your table columns
        }
        echo "</ul>";
    } else {
        echo "No results found for '" . $search_query . "'.";
    }
}
?>
