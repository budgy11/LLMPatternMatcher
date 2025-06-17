  </div>

</body>
</html>


<?php
// Assume you have a database connection established
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ... (rest of the code from above, but replace the $data array)

// Example using MySQLi:
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = htmlspecialchars($searchTerm);

$sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Process the results
    while($row = $result->fetch_assoc()) {
        //  $row is an associative array containing the data from the database row
        //  Process $row as needed to display the results
    }
} else {
    echo "<p>No results found for: " . $searchTerm . "</p>";
}

$conn->close();
?>
