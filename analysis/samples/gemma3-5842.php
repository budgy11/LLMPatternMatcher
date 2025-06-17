    <?php endif; ?>

</body>
</html>


<?php

//Example of connecting to a database.
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//Get the search term from the user (e.g., from a form)
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Perform the search
$sql = "SELECT * FROM products WHERE name LIKE '%" . $searchTerm . "%'";
$result = $conn->query($sql);

$searchResults = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $searchResults[$row['id']] = $row['name'] . " - " . $row['description'];
    }
}

// Close the connection
$conn->close();

?>
