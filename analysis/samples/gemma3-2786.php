

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating   The rating given by the reviewer (e.g., 1-5).
 * @param string $comment The user's comment/review text.
 * @param int $userId (Optional)  The ID of the user submitting the review. If not provided, it will generate a unique ID.
 *
 * @return array An array containing:
 *   - 'success': True if the review was created/updated successfully, false otherwise.
 *   - 'message': A message indicating the result of the operation.
 *   - 'reviewId': The ID of the newly created review, or the existing review ID if updated.
 *   - 'review': The review data as an associative array.
 */
function createOrUpdateReview(string $productId, string $username, string $rating, string $comment, $userId = null): array
{
    // Input validation
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'All fields are required.',
            'reviewId' => null,
            'review' => null,
        ];
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return [
            'success' => false,
            'message' => 'Rating must be a number between 1 and 5.',
            'reviewId' => null,
            'review' => null,
        ];
    }

    // Create a unique review ID (if not provided)
    if ($userId === null) {
        $userId = generateUniqueId();
    }

    // Data to be saved to the database
    $reviewData = [
        'productId' => $productId,
        'userId' => $userId,
        'username' => $username,
        'rating' => $rating,
        'comment' => $comment,
        'created_at' => date('Y-m-d H:i:s'),  // Add timestamp for tracking
    ];

    // Simulate database interaction (Replace with your actual database logic)
    $success = saveReviewToDatabase($reviewData);

    if ($success) {
        return [
            'success' => true,
            'message' => 'Review created/updated successfully.',
            'reviewId' => $userId,
            'review' => $reviewData,
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to save review to database.',
            'reviewId' => null,
            'review' => null,
        ];
    }
}

/**
 * Placeholder function to simulate database interaction.
 * Replace with your actual database logic.
 *
 * @param array $reviewData  The review data to save.
 * @return bool True if the save was successful, false otherwise.
 */
function saveReviewToDatabase(array $reviewData): bool
{
    // *** Replace this with your database logic ***
    // This is just a placeholder for demonstration purposes.

    // Example:  Simulate a successful save.
    // You would typically query your database here.
    //  For example:
    //  $result = $db->insert('reviews', $reviewData);
    //  return $result === false;  // or use $result['insert_id'] to get the ID.

    // In this example, we always return true to simulate success.
    return true;
}


/**
 * Placeholder function to generate a unique ID.
 * Replace with your actual unique ID generation logic.
 */
function generateUniqueId(): string
{
    // *** Replace this with your unique ID generation logic ***
    // Example:
    // return md5(time());  // A simple example.
    return 'unique-review-' . uniqid(); // A more robust example using uniqid()
}


/**
 *  Example Usage:
 */

// Create a new review
$reviewResult = createOrUpdateReview('123', 'JohnDoe', 4, 'Great product! Highly recommended.');

if ($reviewResult['success']) {
    echo "Review created successfully! ID: " . $reviewResult['reviewId'] . "<br>";
    print_r($reviewResult['review']); // Output the review data
} else {
    echo "Error creating review: " . $reviewResult['message'] . "<br>";
}

// Update an existing review (assuming a review ID exists)
$reviewResult = createOrUpdateReview('123', 'JaneSmith', 5, 'Excellent value for money.', 'review-1');

if ($reviewResult['success']) {
    echo "Review updated successfully! ID: " . $reviewResult['reviewId'] . "<br>";
    print_r($reviewResult['review']);
} else {
    echo "Error updating review: " . $reviewResult['message'] . "<br>";
}
?>
