```php
<?php
require_once 'database.php'; // Include the database connection

$sql = "SELECT * FROM products";
$result = executeQuery($conn, $sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["product_id"] . "</td>";
        echo "<td>" . $row["product_name"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
```