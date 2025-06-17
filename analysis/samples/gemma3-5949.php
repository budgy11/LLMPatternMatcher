

<?php

// Assuming you have a database connection established (e.g., $db)

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Sanitize the search term - Important for security!
$searchTerm = htmlspecialchars(trim($searchTerm)); // Prevents XSS attacks

// Prevent empty searches from running a query
if (empty($searchTerm)) {
    $results = []; // Return an empty array if no search term
    echo '<p>Please enter a search term.</p>';
} else {
    //  Construct your SQL query.  This is an example and you'll need to
    //  adjust it to match your database schema and desired search behavior.
    $tableName = 'your_table_name'; // Replace with your table name
    $columnName = 'your_column_name'; // Replace with the column you want to search
    $sql = "SELECT * FROM $tableName WHERE $columnName LIKE '%$searchTerm%'";


    // Execute the query
    $result = mysqli_query($db, $sql);  // Replace $db with your database connection

    // Check if the query was successful
    if ($result) {
        $results = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }

        // Display the results (example)
        if (count($results) > 0) {
            echo '<h2>Search Results for: "' . $searchTerm . '"</h2>';
            echo '<table border="1">';
            echo '<thead><tr><th>ID</th><th>Column 1</th><th>Column 2</th></tr></thead>';  // Adjust headers
            echo '<tbody>';
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>'; // Adjust based on your table columns
                echo '<td>' . $row['column1'] . '</td>'; // Adjust based on your table columns
                echo '<td>' . $row['column2'] . '</td>'; // Adjust based on your table columns
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No results found for: "' . $searchTerm . '"</p>';
        }
    } else {
        // Handle the error
        echo '<p>Error executing query: ' . mysqli_error($db) . '</p>';
    }
}
?>
