
<!DOCTYPE html>
<html>
<head>
    <title>User Review Form</title>
</head>
<body>

    <h1>Submit a Review</h1>

    <form method="post" action="">  <!-- Important:  'action=""'  means the form posts to the same page -->

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="review">Review Text:</label>
        <textarea id="review" name="review" rows="4" cols="50" required></textarea><br><br>

        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

        <button type="submit">Submit Review</button>
    </form>


    <h2>Existing Reviews</h2>

    <?php
    // Display existing reviews (for demonstration)
    if (isset($_SESSION['reviews'])) {
        echo '<h3>Reviews:</h3>';
        echo '<ul>';
        foreach ($_SESSION['reviews'] as $review) {
            echo '<li><strong>Username:</strong> ' . htmlspecialchars($review['username']) . '<br>';
            echo '<strong>Review:</strong> ' . htmlspecialchars($review['review']) . '<br>';
            echo '<strong>Rating:</strong> ' . $review['rating'] . ' (Submitted: ' . date('Y-m-d H:i:s', $review['timestamp']) . ')</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No reviews yet.</p>';
    }
    ?>
