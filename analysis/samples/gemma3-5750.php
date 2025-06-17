

<?php

// Assuming you have a database connection established (e.g., $conn)

// 1. Get the search term from the search bar
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// 2. Sanitize the search term (IMPORTANT for security!)
$searchTerm = htmlspecialchars($searchTerm); // Escapes HTML entities
$searchTerm = trim($searchTerm); // Removes leading/trailing whitespace

// 3. Query the database
if ($searchTerm) {
    $query = "SELECT * FROM your_table_name WHERE your_column_name LIKE '%" . $searchTerm . "%'";
    $result = mysqli_query($conn, $query);

    // 4. Display the results (or a message if no results)
    if ($result) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . $row['your_column_name'] . '</li>'; // Replace 'your_column_name' with the actual column name
        }
        echo '</ul>';
    } else {
        echo 'No results found for: ' . $searchTerm;
    }

    // Close the database connection (recommended)
    mysqli_close($conn);
} else {
    // If no search term is provided, you can display all the data or a default message
    echo '<h3>Search for:</h3>';
    echo '<input type="text" name="search" placeholder="Enter search term">';
}

?>
