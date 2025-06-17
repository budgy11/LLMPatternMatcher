

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic data sanitization and validation to prevent common issues.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $rating      The rating given by the user (1-5).
 * @param string $comment     The review comment.
 * @param int    $userId      The ID of the user submitting the review.
 *
 * @return array An array containing:
 *   - 'success': True if the review was created/updated successfully, false otherwise.
 *   - 'message':  A message describing the result of the operation.
 *   - 'review': The newly created or updated review object (if successful).
 */
function create_review(string $productId, string $rating, string $comment, int $userId): array
{
    // Sanitize and Validate Inputs
    $productId = filter_var($productId, FILTER_SANITIZE_STRING, FILTER_STRIP); // Prevent XSS
    $rating = filter_var($rating, FILTER_VALIDATE_INT, array("min" => 1, "max" => 5));
    $comment = filter_var($comment, FILTER_SANITIZE_STRING, FILTER_STRIP);

    if(empty($productId) || empty($rating) || empty($comment) || $rating === null || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Invalid input.  Please check your input values.',
            'review' => null
        ];
    }

    // --- Database Logic - REPLACE WITH YOUR OWN DATABASE CONNECTION ---
    // Example using a mock database
    $reviews = []; // Simulate a database

    // Generate a unique review ID (for demonstration only - use a real unique ID)
    $reviewId = uniqid();

    // Create a review object (you can customize this)
    $review = [
        'reviewId' => $reviewId,
        'productId' => $productId,
        'userId' => $userId,
        'rating' => $rating,
        'comment' => $comment,
        'timestamp' => time()
    ];

    // Add the review to the reviews array
    $reviews[$reviewId] = $review;

    // --- End Database Logic ---


    return [
        'success' => true,
        'message' => 'Review created successfully!',
        'review' => $review
    ];
}


// --- Example Usage ---
// Create a review
$result = create_review('product123', 4, 'Great product!', 123);

if ($result['success']) {
    echo "Review created successfully:<br>";
    echo "Review ID: " . $result['review']['reviewId'] . "<br>";
    echo "Product ID: " . $result['review']['productId'] . "<br>";
    echo "Rating: " . $result['review']['rating'] . "<br>";
    echo "Comment: " . $result['review']['comment'] . "<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}

// Example with invalid input
$result = create_review('', 3, 'Bad product!', 123);
if ($result['success']) {
    echo "Review created successfully:<br>";
} else {
    echo "Error creating review: " . $result['message'] . "<br>";
}
?>
