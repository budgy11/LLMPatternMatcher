```php
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
```