
<!DOCTYPE html>
<html>
<head>
    <title>User Reviews</title>
</head>
<body>

<h1>Add a Review</h1>

<form method="POST" action="">
    <label for="productId">Product ID:</label>
    <input type="text" id="productId" name="productId" value="product123" required><br><br>

    <label for="userId">User ID:</label>
    <input type="text" id="userId" name="userId" value="user456" required><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <label for="comment">Review Comment:</label>
    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

    <button type="submit">Submit Review</button>
</form>

<?php
if (isset($_POST['productId']) && isset($_POST['userId']) && isset($_POST['rating']) && isset($_POST['comment'])) {
    $productId = $_POST['productId'];
    $userId = $_POST['userId'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $reviewId = createOrUpdateReview($productId, $userId, $rating, $comment);

    if (isset($reviewId) && $reviewId > 0) {
        echo "<p>Review created successfully with ID: " . $reviewId . "</p>";
    } else {
        echo "<p style='color: red;'>Error creating review: " . $reviewId['error'] . "</p>";
    }
}
?>
