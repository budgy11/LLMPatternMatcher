

<?php

/**
 * User Review Function
 *
 * This function allows you to handle user reviews for a product or service.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating A numerical rating (e.g., 1-5) for the product/service.
 * @param string $comment The user's written review comment.
 * @param array $reviews An existing array of reviews to append to.  If null, creates a new array.
 *
 * @return array An array of reviews, with the new review added.
 */
function addReview(string $productId, string $username, int $rating, string $comment, array $reviews = []): array
{
    // Input Validation (Important for security and data integrity)
    if (empty($productId)) {
        throw new InvalidArgumentException("Product ID cannot be empty.");
    }
    if (empty($username)) {
        throw new InvalidArgumentException("Username cannot be empty.");
    }
    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("Rating must be between 1 and 5.");
    }

    // Create a review object (for easier management)
    $review = [
        'productId' => $productId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'date' => date("Y-m-d H:i:s") // Add a timestamp for tracking.
    ];

    // Add the review to the array
    $reviews[] = $review;

    return $reviews;
}


// ------------------- Example Usage -------------------
//  To use this, you'd typically store this function in a file (e.g., review_handler.php)
//  and then include it in your application file: require_once 'review_handler.php';

// Example 1:  Adding a new review
try {
    $reviews = addReview('product123', 'JohnDoe', 4, 'Great product, works as expected!', []);
    print_r($reviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "
";
}


// Example 2: Adding to an existing reviews list
try {
    $reviews = addReview('product456', 'JaneSmith', 5, 'Excellent service, highly recommended!', $reviews);
    print_r($reviews);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "
";
}


// Example 3: Error Handling - Invalid Rating
try {
    addReview('product789', 'PeterPan', 6, 'Too expensive', []);
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "
"; // Expected output: "Error: Rating must be between 1 and 5."
}

?>
