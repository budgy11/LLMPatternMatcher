    <?php endif; ?>

</body>
</html>


<?php
// ... (HTML form as before) ...

// Perform the search
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Database connection (replace with your credentials)
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

// Prepare the SQL query
$sql = "SELECT product_name FROM products WHERE product_name LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("s", "%" . $searchTerm . "%"); // % is a wildcard for LIKE

// Execute the query
$stmt->execute();

// Get the results
$results = $stmt->get_result();

// Free the statement
$stmt->close();

// ... (Display results based on $results) ...
?>
