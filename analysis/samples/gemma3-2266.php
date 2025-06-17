

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $username  The username of the reviewer.
 * @param string $rating   The rating (e.g., 1-5).
 * @param string $comment  The review text.
 * @param array $reviews  (Optional) An array of existing reviews to persist.
 *
 * @return array An updated array of reviews including the new review.
 */
function add_review(string $productId, string $username, string $rating, string $comment, array &$reviews = []) {
    // Input Validation (Basic - can be expanded)
    if (empty($productId)) {
        throw new InvalidArgumentException("Product ID cannot be empty.");
    }
    if (empty($username)) {
        throw new InvalidArgumentException("Username cannot be empty.");
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be a number between 1 and 5.");
    }
    if (empty($comment)) {
        throw new InvalidArgumentException("Comment cannot be empty.");
    }

    // Create a new review object
    $review = [
        'product_id' => $productId,
        'username'   => $username,
        'rating'     => $rating,
        'comment'    => $comment,
        'timestamp'  => time() // Add a timestamp for sorting/filtering
    ];

    // Add the new review to the array
    $reviews[] = $review;

    return $reviews;
}


/**
 * Display Reviews Function
 *
 * This function formats and displays the reviews.
 *
 * @param array $reviews  The array of reviews to display.
 *
 * @return void  Prints the formatted reviews to the console.  Can be modified to output HTML.
 */
function display_reviews(array $reviews) {
    echo "<pre>"; // Use <pre> tags for formatted output
    foreach ($reviews as $review) {
        echo "Product ID: " . $review['product_id'] . "
";
        echo "Username: " . $review['username'] . "
";
        echo "Rating: " . $review['rating'] . "
";
        echo "Comment: " . $review['comment'] . "
";
        echo "Timestamp: " . date("Y-m-d H:i:s", $review['timestamp']) . "
";
        echo "---
";
    }
    echo "</pre>";
}



// Example Usage:
try {
    // Initialize reviews (can be persisted to a database)
    $reviews = [];

    // Add some reviews
    $reviews = add_review("product123", "John Doe", 4, "Great product!  Works as expected.", $reviews);
    $reviews = add_review("product456", "Jane Smith", 5, "Excellent value for the money.", $reviews);
    $reviews = add_review("product123", "Peter Jones", 3, "Could be better.", $reviews);

    // Display the reviews
    display_reviews($reviews);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}


?>
