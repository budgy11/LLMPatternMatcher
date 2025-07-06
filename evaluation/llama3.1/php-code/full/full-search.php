
<?php
// config.php
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
?>

<?php
// Include config.php for database connection
include 'config.php';

// Check if form is submitted
if (isset($_POST['search'])) {
    // Prepare query parameters
    $searchTerm = $_POST['searchTerm'];
    
    // SQL query to retrieve search results
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"]. " - Description: " . $row["description"]. " - Price: " . $row["price"]. "<br>";
        }
    } else {
        echo "No results found.";
    }
}

// Close database connection
$conn->close();

?>

<!-- Search form -->
<form action="" method="post">
  <label>Search:</label>
  <input type="text" name="searchTerm" required>
  <button type="submit" name="search">Search</button>
</form>

