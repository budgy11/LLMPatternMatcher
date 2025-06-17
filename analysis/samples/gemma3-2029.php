

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It provides a simple interface for storing and managing user feedback.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's comment/feedback.
 *
 * @return array An array containing the result of the operation:
 *               - 'success': true if the review was created/updated successfully, false otherwise.
 *               - 'message': A descriptive message about the result.
 *               - 'review': The newly created or updated review object (if successful).
 */
function createOrUpdateReview(string $productId, string $userId, string $rating, string $comment) {
    // Validate input -  Important for security and data integrity
    if (empty($productId) || empty($userId) || empty($rating) || empty($comment)) {
        return [
            'success' => false,
            'message' => 'Missing required fields.',
            'review' => null,
        ];
    }

    // Validate rating to ensure it's numeric and within a valid range
    if (!is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5) {
        return [
            'success' => false,
            'message' => 'Invalid rating. Rating must be a number between 1 and 5.',
            'review' => null,
        ];
    }


    // ***  Simulating Database Interaction - Replace with your actual database code ***
    // This section simulates creating a review in a database.
    // In a real application, you'd use a database query (e.g., using PDO, MySQLi).
    $review = [
        'product_id' => $productId,
        'user_id' => $userId,
        'rating' => intval($rating), // Ensure integer rating
        'comment' => $comment,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    // **  Store the review (simulated)  **
    // In a real app, you'd save this to a database.
    // For example:
    // $db->insert('reviews', $review);

    // **  Simulated Success  **
    return [
        'success' => true,
        'message' => 'Review created successfully!',
        'review' => $review,
    ];
}


// --- Example Usage ---
// $result = createOrUpdateReview('product123', 'user456', 4, 'Great product, highly recommended!');
//
// if ($result['success']) {
//     echo "Review created: " . json_encode($result['review']) . "
";
// } else {
//     echo "Error creating review: " . $result['message'] . "
";
// }

// Example with invalid rating:
// $result = createOrUpdateReview('product123', 'user456', 6, 'Excellent!');
// if ($result['success']) {
//     echo "Review created: " . json_encode($result['review']) . "
";
// } else {
//     echo "Error creating review: " . $result['message'] . "
";
// }

?>
