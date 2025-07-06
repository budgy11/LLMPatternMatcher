<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php
    $product_id = $_GET['product_id'];

    include 'database.php';

    $sql = "SELECT * FROM products WHERE product_id = " . $product_id;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo $product['product_name'];
    } else {
        echo "Product not found.";
    }
    ?></h1>

    <img src='" . $product['image_url'] . "' alt='" . $product['product_name'] . "'>";
    echo "<p>" . $product['description'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";

    // Reviews
    echo "<h2>Reviews for " . $product['product_name'] . "</h2>";

    include 'review.php'; //Include the review.php file
    ?>
</body>
</html>
