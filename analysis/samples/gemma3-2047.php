
<!DOCTYPE html>
<html>
<head>
    <title>User Review Form</title>
</head>
<body>

    <h1>Submit a Review</h1>

    <form method="POST" action="">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required><br><br>

        <label for="review_text">Review Text:</label><br>
        <textarea id="review_text" name="review_text" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Submit Review</button>
    </form>

    <?php
    if(isset($_POST['product_id']) && isset($_POST['review_text'])) {
        $productId = $_POST['product_id'];
        $reviewText = $_POST['review_text'];

        $result = createAndDisplayReview($productId, $reviewText);

        if ($result['success']) {
            echo "<p>Review submitted successfully!  Review ID: " . $result['reviewId'] . "</p>";
        } else {
            echo "<p>Error submitting review: " . $result['message'] . "</p>";
        }
    }

    ?>
