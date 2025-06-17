

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews for a product or service.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The review comment.
 * @param array $reviews (Optional) An array of existing reviews for the product.  Defaults to an empty array.
 *
 * @return array An array of reviews, including the new review added.
 */
function createAndManageReviews(string $productId, string $username, string $rating, string $comment, array &$reviews = []): array {
    // Validate input (Basic example - enhance with more robust validation)
    if (!is_numeric($rating) || !is_int($rating) || $rating < 1 || $rating > 5) {
        return $reviews; // Return existing reviews if invalid rating
    }

    // Add the new review
    $review = [
        'productId' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time() // Add a timestamp for sorting
    ];
    $reviews[] = $review;

    // Sort reviews by timestamp (most recent first)
    usort($reviews, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    return $reviews;
}

// --------------------- Example Usage ---------------------

// Initialize an empty array of reviews
$reviews = [];

// Create some reviews
$reviews = createAndManageReviews('product123', 'john.doe', 4, 'Great product!  Highly recommended.', $reviews);
$reviews = createAndManageReviews('product123', 'jane.smith', 5, 'Excellent service and fast delivery.', $reviews);
$reviews = createAndManageReviews('product456', 'peter.jones', 3, 'It was okay, nothing special.', $reviews); // Another product

// Display the reviews
echo "<h2>Reviews for Product 123:</h2>";
if (count($reviews)) {
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>" . $review['username'] . ":</strong> " . $review['rating'] . " - " . $review['comment'] . " (Timestamp: " . date("Y-m-d H:i:s", $review['timestamp']) . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews yet.</p>";
}

echo "<h2>Reviews for Product 456:</h2>";
if (count($reviews)) {
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>" . $review['username'] . ":</strong> " . $review['rating'] . " - " . $review['comment'] . " (Timestamp: " . date("Y-m-d H:i:s", $review['timestamp']) . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No reviews yet.</p>";
}
?>
