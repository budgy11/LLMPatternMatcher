

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It's a simplified example and doesn't include full database interaction or
 * robust error handling.  You'll need to adapt it to your specific database
 * and security requirements.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $name The user's name.
 * @param string $comment The user's review comment.
 * @param int    $rating  The rating given by the user (e.g., 1-5).
 * @param int    $userId  (Optional) The ID of the user. If not provided, a new user is created.
 *
 * @return array  An array containing:
 *               - 'success': True if the review was created/updated successfully, false otherwise.
 *               - 'message':  A message indicating the status of the operation.
 *               - 'review': The newly created or updated review object (if successful).
 */
function createOrUpdateReview(string $productId, string $name, string $comment, int $rating, int $userId = null): array
{
    // Validate input (Basic - enhance this for production)
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID is required.', 'review' => null];
    }
    if (empty($name)) {
        return ['success' => false, 'message' => 'User name is required.', 'review' => null];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Review comment is required.', 'review' => null];
    }
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'review' => null];
    }
    if (!is_numeric($userId)) {
        $userId = null; // Fallback to create a new user
    }
    // ------------------ Database Interaction (Simulated for this example) ------------------
    // In a real application, you'd replace this with your actual database queries.

    // Simulate a user ID (if not provided)
    if ($userId == null) {
        $userId = uniqid(); // Generate a unique ID
    }


    // Simulate a review object
    $review = [
        'productId' => $productId,
        'userId' => $userId,
        'name' => $name,
        'comment' => $comment,
        'rating' => $rating,
        'dateCreated' => date('Y-m-d H:i:s')
    ];

    // Simulate successful operation
    return ['success' => true, 'message' => 'Review created/updated successfully.', 'review' => $review];
}


// ------------------ Example Usage ------------------

// Create a new review
$result = createOrUpdateReview('product123', 'John Doe', 'Great product!', 5);
print_r($result);

// Update an existing review (simulated)
$result = createOrUpdateReview('product123', 'Jane Smith', 'Improved version!', 4, 123);
print_r($result);


// Test cases - Demonstrate error handling
$result = createOrUpdateReview('', 'User', 'Review', 3);
print_r($result);

$result = createOrUpdateReview('product456', 'User', '', 2);
print_r($result);

$result = createOrUpdateReview('product789', 'User', 'Review', 6);
print_r($result);

?>
