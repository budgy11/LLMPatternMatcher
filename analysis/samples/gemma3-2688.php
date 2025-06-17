

<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId The unique identifier of the product.
 * @param string $username The username of the reviewer.
 * @param string $rating A rating from 1 to 5.
 * @param string $comment The user's review comment.
 * @param array $reviews (Optional) Array to store reviews.  If empty, creates a new array.
 * 
 * @return array  The array of reviews, updated with the new review.
 */
function addReview(string $productId, string $username, int $rating, string $comment, array $reviews = []): array
{
    // Input validation - Essential for security and data integrity
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

    // Create a review object
    $review = [
        'product_id' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s') // Add a timestamp for review history
    ];

    // Add the review to the array
    $reviews[] = $review;

    return $reviews;
}



/**
 * Display Reviews Function
 * 
 * This function retrieves and formats reviews for display.
 *
 * @param array $reviews An array of review objects.
 * 
 * @return string A formatted string of reviews.
 */
function displayReviews(array $reviews): string
{
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $output = "<ul>";
    foreach ($reviews as $review) {
        $output .= "<li>";
        $output .= "<strong>" . htmlspecialchars($review['username']) . ":</strong> " . htmlspecialchars($review['comment']) . " (" . $review['rating'] . "/5) - " . htmlspecialchars($review['date']) . "</li>";
    }
    $output .= "</ul>";
    return $output;
}


// Example Usage:

// Initialize an empty reviews array.  This is good practice.
$productReviews = [];


try {
    // Add some reviews
    $productReviews = addReview("product123", "JohnDoe", 4, "Great product!  I would recommend it.", $productReviews);
    $productReviews = addReview("product123", "JaneSmith", 5, "Excellent quality and fast shipping.", $productReviews);
    $productReviews = addReview("product456", "PeterJones", 3, "It's okay, but could be better.", $productReviews);

    // Display the reviews
    echo displayReviews($productReviews);

} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

?>
