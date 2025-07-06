<?php

$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "ecommerce_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
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
<?php
include 'database.php';

if (isset($_POST['submit_review'])) {
    $product_id = $_GET['product_id'];
    $user_name = $_POST['user_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (" . $product_id . ", '" . $user_name . "', " . $rating . ", '" . $comment . "')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Review submitted successfully!</p>";
    } else {
        echo "<p>Error submitting review: " . $conn->error . "</p>";
    }
}
?>

<form method="post" action="">
    <label for="user_name">User Name:</label>
    <input type="text" id="user_name" name="user_name" required><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <label for="comment">Comment:</label>
    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

    <input type="submit" name="submit_review" value="Submit Review">
</form>
<?php
include 'database.php';

if (isset($_POST['submit_review'])) {
    $product_id = $_GET['product_id'];
    $user_name = $_POST['user_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Using prepared statement
    $sql = "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $user_name); // 's' indicates a string
    $stmt->bind_param("i", $rating); // 'i' indicates an integer
    $stmt->bind_param("s", $comment);

    if ($stmt->execute()) {
        echo "<p>Review submitted successfully!</p>";
    } else {
        echo "<p>Error submitting review: " . $conn->error . "</p>";
    }

    $stmt->close();
}
?>
