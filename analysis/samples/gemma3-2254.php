

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and error handling.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The name of the user submitting the review.
 * @param string $rating The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 *
 * @return array An array containing the result of the operation:
 *               - 'success': True if the review was created/updated successfully, False otherwise.
 *               - 'message': A message indicating the result (e.g., 'Review created successfully', 'Error saving review').
 *               - 'review' (optional): The review data if it was successfully created or updated.
 */
function create_review(string $productId, string $username, string $rating, string $comment) {
  // Input Validation - Crucial for security and data integrity
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.', 'review' => null];
  }
  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.', 'review' => null];
  }
  if (empty($rating)) {
    return ['success' => false, 'message' => 'Rating cannot be empty.', 'review' => null];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Comment cannot be empty.', 'review' => null];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'review' => null];
  }

  // In a real application, you would store this data in a database.
  // This is a simplified example using an associative array.

  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => time()
  ];

  // You would typically save the $review array to a database here.
  // Example:
  // $db = new DatabaseConnection(); // Assuming you have a database connection class
  // $db->insert('reviews', $review);

  // For demonstration purposes, we'll just return the review data.
  return ['success' => true, 'message' => 'Review created successfully.', 'review' => $review];
}


/**
 * Example Usage
 */

// Create a review
$result = create_review('product_123', 'John Doe', 4, 'Great product, highly recommended!');

if ($result['success']) {
  echo "Review created successfully!  Review Details: " . print_r($result['review'], true) . "
";
} else {
  echo "Error creating review: " . $result['message'] . "
";
}

// Example with invalid rating
$result = create_review('product_456', 'Jane Smith', 6, 'Excellent!');

if ($result['success']) {
  echo "Review created successfully!  Review Details: " . print_r($result['review'], true) . "
";
} else {
  echo "Error creating review: " . $result['message'] . "
";
}

?>
