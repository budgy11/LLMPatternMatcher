```php
<!DOCTYPE html>
<html>
<head>
    <title>E-Commerce Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Products</h1>

    <?php
    include 'database.php';

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h2>" . $row['product_name'] . "</h2>";
            echo "<img src='" . $row['image_url'] . "' alt='" . $row['product_name'] . "'>";
            echo "<a href='product.php?product_id=" . $row['product_id'] . "' >View Details</a>";
            echo "</div>";
        }
    } else {
        echo "No products found.";
    }
    ?>
</body>
</html>
```