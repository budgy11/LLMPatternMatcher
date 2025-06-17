
</body>
</html>


<?php
// Connect to your database (replace with your credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the GET request
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Sanitize the search term
$searchTerm = htmlspecialchars($searchTerm);

// Perform the search
$searchResults = [];
if ($searchTerm) {
    // SQL query with LIKE operator for case-insensitive search
    $sql = "SELECT * FROM your_table WHERE item_name LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $searchResults[] = htmlspecialchars($row['item_name']); // Escape the output
        }
    }
}

$conn->close();

// ... (HTML structure as in the previous example) ...
?>
