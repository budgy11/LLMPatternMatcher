

<?php

// Assume you have a database connection established here.  For example:
// $conn = new mysqli("localhost", "username", "password", "database_name");
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }


// Function to handle the search query
function searchDatabase($search_term, $table_name, $search_columns)
{
    global $conn; // Access the global database connection

    $sql = "SELECT * FROM " . $table_name . " WHERE ";

    $where_clauses = [];
    foreach ($search_columns as $column) {
        $where_clauses[] = "`" . $column . "`" . " LIKE '%" . $search_term . "%'";
    }

    $sql .= implode(" OR ", $where_clauses);

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    } else {
        return false;
    }
}

// Get the search term from the form
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Define the table and columns to search within
$table_name = 'products'; // Example: Change this to your table name
$search_columns = ['name', 'description', 'category']; // Example: Columns to search in

// Perform the search
$results = searchDatabase($search_term, $table_name, $search_columns);


?>
