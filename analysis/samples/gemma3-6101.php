
<!DOCTYPE html>
<html>
<head>
    <title>Search Bar Example</title>
</head>
<body>

    <h1>Search</h1>

    <form method="get" action="">
        <input type="text" name="search" placeholder="Enter search term">
        <button type="submit">Search</button>
    </form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $conn)

// Get the search term from the form
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term (Important for security - prevents SQL injection)
$searchTerm = htmlspecialchars(trim($searchTerm));


// --- Example 1: Simple Search Across a Table (e.g., 'products') ---
// This example searches all columns in a table named 'products'

// Check if the search term is empty
if (empty($searchTerm)) {
    // No search term, display all products or a default message
    echo "<p>Enter a search term.</p>";
    // Or, display all products
    // $sql = "SELECT * FROM products";
    // $result = mysqli_query($conn, $sql);
    // if ($result) {
    //     echo "<ul>";
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         echo "<li>" . htmlspecialchars($row['name']) . "</li>"; // Adjust 'name' to the actual column
    //     }
    //     echo "</ul>";
    // } else {
    //     echo "Error querying the database.";
    // }
} else {
    // Perform the search
    $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";  // 'LIKE' is used for partial matches
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . htmlspecialchars($row['name']) . "</li>"; // Adjust 'name' to the actual column
        }
        echo "</ul>";
    } else {
        echo "<p>No products found matching your search.</p>";
    }
}

// --- Example 2:  Searching a Specific Column (e.g., 'name') ---
// This is generally better for performance, especially with large datasets.

// if (empty($searchTerm)) {
//     echo "<p>Enter a search term.</p>";
// } else {
//     $sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
//     $result = mysqli_query($conn, $sql);

//     if ($result) {
//         echo "<ul>";
//         while ($row = mysqli_fetch_assoc($result)) {
//             echo "<li>" . htmlspecialchars($row['name']) . "</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "<p>No products found matching your search.</p>";
//     }
// }



?>
