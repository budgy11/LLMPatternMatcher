```php
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
```