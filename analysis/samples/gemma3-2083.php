

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It demonstrates a basic CRUD (Create, Read, Update, Delete) operation
 * using a simple array to store reviews.  For a production environment,
 * you'd typically use a database.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the user submitting the review.
 * @param string $rating  The rating given (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param array $reviews (Optional) An array of existing reviews.  If not provided, starts empty.
 *
 * @return array  The updated array of reviews.
 */
function create_user_review(string $productId, string $username, string $rating, string $comment, array &$reviews = []): array
{
    // Validate input (basic example - expand for production)
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        return $reviews; // Return existing reviews if input is invalid
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return $reviews; // Return existing reviews for invalid rating
    }

    // Create the review
    $review = [
        'product_id' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time() // Add a timestamp for tracking
    ];

    // Add the review to the array
    $reviews[] = $review;

    return $reviews;
}


// --- Example Usage ---

// Initial reviews (empty array)
$product_reviews = [];

// Create a few reviews
$product_reviews = create_user_review('product123', 'john_doe', 4, 'Great product!', $product_reviews);
$product_reviews = create_user_review('product123', 'jane_smith', 5, 'Excellent value!', $product_reviews);
$product_reviews = create_user_review('product123', 'peter_jones', 3, 'Could be better', $product_reviews);

// Display the reviews
echo "<h2>Reviews for Product 123</h2>";
echo "<ul>";
foreach ($product_reviews as $review) {
    echo "<li><strong>Product ID:</strong> " . $review['product_id'] . "<br>";
    echo "<strong>Username:</strong> " . $review['username'] . "<br>";
    echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
    echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
    echo "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s', $review['timestamp']) . "</li>";
}
echo "</ul>";


// --- Example of Updating a Review (Basic - more robust logic needed in real applications) ---
// Assuming you have a function to get a review by ID.  This is a simplified example.

/**
 *  Gets a review by its ID (simplified - replace with database query in production)
 * @param int $reviewId The ID of the review to retrieve.
 * @return array|null The review object if found, null otherwise.
 */
function get_review_by_id(array $reviews, int $reviewId) {
  foreach ($reviews as $review) {
    if ($review['id'] == $reviewId) {
      return $review;
    }
  }
  return null;
}


$review_to_update = get_review_by_id($product_reviews, 1); // Assuming review 1 exists
if ($review_to_update) {
    $review_to_update['comment'] = "This is an updated comment!";
    $product_reviews = $review_to_update; // Update the array
    echo "<br><h2>Updated Review:</h2>";
    echo "<pre>";
    print_r($review_to_update);
    echo "</pre>";
} else {
    echo "<p>Review not found.</p>";
}

?>
