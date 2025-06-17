

<?php

/**
 * This function handles user reviews, allowing for adding, displaying,
 * and potentially updating or deleting them.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $name The reviewer's name.
 * @param string $comment The user's review comment.
 * @param int $rating  The rating given by the user (1-5).
 * @param int $userId (Optional) The ID of the user submitting the review.
 *                    If not provided, a unique ID will be generated.
 * @return array An array containing:
 *   - 'success': True if the review was created successfully, false otherwise.
 *   - 'message': A message indicating the status of the review creation.
 *   - 'reviewId': The ID of the newly created review (if successful).
 */
function createReview($productId, $name, $comment, $rating, $userId = null) {
  // Input Validation - Essential for security and data integrity
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.', 'reviewId' => null];
  }
  if (empty($name)) {
    return ['success' => false, 'message' => 'Reviewer name cannot be empty.', 'reviewId' => null];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Review comment cannot be empty.', 'reviewId' => null];
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'reviewId' => null];
  }

  // Unique ID Generation (optional - if not provided by user)
  if ($userId === null) {
    $userId = generateUniqueUserId();
  }

  // Data Storage (Example: Using an array.  For a real application,
  // you'd use a database)
  $newReview = [
    'productId' => $productId,
    'name' => $name,
    'comment' => $comment,
    'rating' => $rating,
    'userId' => $userId,
    'timestamp' => time()
  ];

  // Simulate Storing Review (Replace with database insertion)
  // In a real application, you would insert this data into a database.
  // For example:
  // $result = insertReviewIntoDatabase($newReview);

  // Simulate successful insertion
  // $result = true;

  // Example response -  Adjust based on your actual storage implementation
  return ['success' => true, 'message' => 'Review created successfully!', 'reviewId' => $userId];
}


/**
 *  A placeholder function for generating a unique user ID.
 *  In a real application, this would likely use a database sequence or
 *  a UUID generator.
 *
 * @return int A unique user ID.
 */
function generateUniqueUserId() {
  // This is a simple example.  In a production environment,
  // use a more robust method for generating unique IDs.
  return mt_rand(100000, 999999);
}


// Example Usage:
$product_id = '123';
$reviewer_name = 'John Doe';
$review_comment = 'Great product! Highly recommended.';
$rating = 5;

$review_result = createReview($product_id, $reviewer_name, $review_comment, $rating);

if ($review_result['success']) {
  echo "Review created successfully! Review ID: " . $review_result['reviewId'] . "<br>";
} else {
  echo "Error creating review: " . $review_result['message'] . "<br>";
}

// Example with a provided user ID:
$user_id = 42;
$review_result2 = createReview($product_id, $reviewer_name, $review_comment, $rating, $user_id);

if ($review_result2['success']) {
  echo "Review created successfully (using provided ID)! Review ID: " . $review_result2['reviewId'] . "<br>";
} else {
  echo "Error creating review (using provided ID): " . $review_result2['message'] . "<br>";
}
?>
