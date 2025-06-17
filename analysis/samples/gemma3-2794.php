

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The review comment.
 * @param array $reviews An array of existing reviews for this product (optional, for updating).
 *
 * @return array An array containing the updated reviews array or a new array if no reviews existed.
 */
function createReview(string $productId, string $username, string $rating, string $comment, array $reviews = []): array
{
    // Input Validation (Important!)
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

    // Create the new review
    $newReview = [
        'product_id' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // Add the new review to the existing array
    $reviews[] = $newReview;

    return $reviews;
}


/**
 * Function to display reviews for a product.
 *
 * @param array $reviews An array of reviews.
 */
function displayReviews(array $reviews) {
    echo "<h2>Reviews for Product ID: " . $productId = key($reviews) . "</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>Username:</strong> " . htmlspecialchars($review['username']) . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
        echo "<strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "<br>";
        echo "<strong>Timestamp:</strong> " . date("Y-m-d H:i:s", $review['timestamp']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}


// Example Usage:

// Initialize Reviews (Optional - can be done from a database)
$productReviews = [];

// Example 1: Creating a new review
try {
    $productReviews = createReview("123", "JohnDoe", 4, "Great product, highly recommended!");
    displayReviews($productReviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Example 2:  Adding another review
try {
    $productReviews = createReview("123", "JaneSmith", 5, "Excellent value for money!", $productReviews);
    displayReviews($productReviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

// Example 3:  Demonstrating input validation
try {
    $productReviews = createReview("", "Invalid", 2, "Bad review", $productReviews); // Empty product ID
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

?>
