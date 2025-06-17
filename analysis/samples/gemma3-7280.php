    <br><br>

    <button type="submit">Place Order</button>
</form>

<br>
<a href="view_purchases.php">View Purchases</a>  <!-- Link to a page to display purchases -->

</body>
</html>


<?php

// Database connection (same as above - adjust if needed)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_database_name";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all purchases
$sql = "SELECT * FROM purchases";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Purchase History</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<h3>Purchase Details</h3>";
        echo "<p>First Name: " . htmlspecialchars($row["firstName"]) . "</p>";
        echo "<p>Last Name: " . htmlspecialchars($row["lastName"]) . "</p>";
        echo "<p>Email: " . htmlspecialchars($row["email"]) . "</p>";
        echo "<p>Quantity: " . htmlspecialchars($row["quantity"]) . "</p>";
        echo "<p>Price per Item: " . htmlspecialchars($row["price"]) . "</p>";
        echo "<hr>";
    }
} else {
    echo "<p>No purchases found.</p>";
}

$conn->close();
?>
