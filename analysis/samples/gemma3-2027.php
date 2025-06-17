

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It demonstrates basic CRUD operations with error handling and validation.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The review comment.
 * @param int $reviewId (optional) The ID of the review to update. If not provided, a new review is created.
 * @return array Returns an array containing the response data (success/failure message, updated review data, or error message).
 */
function create_review(string $productId, string $username, string $rating, string $comment, int $reviewId = 0)
{
    // Validate inputs
    if (empty($productId)) {
        return ['success' => false, 'message' => 'Product ID cannot be empty.'];
    }
    if (empty($username)) {
        return ['success' => false, 'message' => 'Username cannot be empty.'];
    }
    if (empty($rating)) {
        return ['success' => false, 'message' => 'Rating cannot be empty.'];
    }
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.'];
    }
    if (empty($comment)) {
        return ['success' => false, 'message' => 'Comment cannot be empty.'];
    }

    // ---  Simulated Database Interaction (Replace with your actual database logic) ---
    // For demonstration purposes, we'll use an in-memory array.
    $reviews = [];  // Initialize the reviews array

    // Check if the review with the given ID already exists
    if ($reviewId > 0) {
        if (isset($reviews[$reviewId])) {
            // Update existing review
            $reviews[$reviewId] = [
                'product_id' => $productId,
                'username' => $username,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => time() // Add timestamp for creation
            ];
        } else {
            return ['success' => false, 'message' => 'Review not found with ID: ' . $reviewId];
        }
    } else {
        // Create a new review
        $new_review_id = count($reviews) + 1;
        $review = [
            'product_id' => $productId,
            'username' => $username,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => time()  // Add timestamp for creation
        ];
        $reviews[$new_review_id] = $review;
    }

    // --- End Simulated Database Interaction ---

    return ['success' => true, 'message' => 'Review created successfully.', 'review' => $reviews[$new_review_id] ];
}


// Example Usage:
$response = create_review('123', 'JohnDoe', 4, 'Great product!', 1);
print_r($response);
echo "<br>";

$response = create_review('456', 'JaneSmith', 5, 'Excellent value!', 2);
print_r($response);
echo "<br>";

$response = create_review('789', 'PeterJones', 3, 'Okay', 3);
print_r($response);
echo "<br>";

$response = create_review('101', 'AliceBrown', 2, 'Not impressed', 4); // Simulate updating a review
print_r($response);
echo "<br>";
?>
