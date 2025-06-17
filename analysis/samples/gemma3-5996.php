

<?php

// Assuming you have a database connection established
// (e.g., $conn = mysqli_connect("localhost", "username", "password", "database_name");)

// Example data (replace with your actual database query)
$data = [
    ["id" => 1, "name" => "Apple", "description" => "A delicious fruit."],
    ["id" => 2, "name" => "Banana", "description" => "A yellow fruit."],
    ["id" => 3, "name" => "Orange", "description" => "A citrus fruit."],
    ["id" => 4, "name" => "Grape", "description" => "Small, sweet fruit."],
];


// Get the search term from the search bar (assuming it's in a form field named "search")
$searchTerm = isset($_GET["search"]) ? $_GET["search"] : "";

// Sanitize the search term (important to prevent XSS)
$searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, "UTF-8");

// Case-insensitive search
$searchTerm = strtolower($searchTerm);


// Build the SQL query (Use prepared statements for security!)
$sql = "SELECT * FROM your_table_name WHERE name LIKE '%" . $searchTerm . "%' OR description LIKE '%" . $searchTerm . "%'";

// Prepare and execute the query (RECOMMENDED for security)
// $stmt = mysqli_prepare($conn, $sql);
// mysqli_stmt_bind_string($stmt, "name", $searchTerm); // More efficient for string binding
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);

// Alternative: Simple query (less secure - avoid in production)
$result = mysqli_query($conn, $sql);

// Display the search bar
echo '<form method="GET" action="" style="margin-bottom: 10px;">
        <input type="text" name="search" placeholder="Search..." value="' . $searchTerm . '">
        <button type="submit">Search</button>
    </form>';

// Display the results
if ($result) {
    echo '<h2>Search Results:</h2>';
    if (mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>ID: ' . $row["id"] . ' - ' . $row["name"] . ' - ' . $row["description"] . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No results found for "' . $searchTerm . '".</p>';
    }

    // Close the result set
    mysqli_free_result($result);
} else {
    echo '<p>Error executing the query: ' . mysqli_error($conn) . '</p>';
}

// Close the database connection
mysqli_close($conn);

?>
