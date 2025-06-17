

<?php

/**
 * User Review Function
 *
 * This function handles the creation, retrieval, updating, and deletion of user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param int $id Optional: The ID of the review to update. If not provided, a new review is created.
 *
 * @return array Returns an array with status and details.
 *               - 'status' => 'success' or 'error'
 *               - 'message' =>  A descriptive message.
 *               - 'review' => (if success) an associative array containing the review data (id, productId, userId, rating, comment, creationDate)
 *
 */
function createOrUpdateReview(string $productId, string $userId, string $rating, string $comment, ?int $id = null): array {
  // Validate inputs - Basic examples, extend as needed for your application
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return ['status' => 'error', 'message' => 'Invalid rating. Rating must be a number between 1 and 5.'];
  }
  if (empty($comment)) {
    return ['status' => 'error', 'message' => 'Review comment cannot be empty.'];
  }
  // Ideally, you would also validate $userId - check against user database
  // For this example, we assume it's valid.

  // Create a new review
  if (is_null($id)) {
    $review = [
      'productId' => $productId,
      'userId' => $userId,
      'rating' => $rating,
      'comment' => $comment,
      'creationDate' => date('Y-m-d H:i:s')  // Use appropriate timestamp format
    ];
  } else {
    // Update an existing review
    // In a real application, you'd query a database here to update the review
    // This is a placeholder for demonstration.
    $review = [
      'id' => $id,
      'productId' => $productId,
      'userId' => $userId,
      'rating' => $rating,
      'comment' => $comment,
      'creationDate' => date('Y-m-d H:i:s')
    ];
  }


  // In a real application, you'd save the $review data to a database here.
  // For this example, we're just returning the review data.

  return ['status' => 'success', 'message' => 'Review created/updated successfully.', 'review' => $review];
}


// Example Usage
$productId = '123';
$userId = 'user123';
$rating = 4;
$comment = 'Great product!  I highly recommend it.';

$result = createOrUpdateReview($productId, $userId, $rating, $comment);

if ($result['status'] === 'success') {
  echo "Review created/updated successfully: " . PHP_EOL;
  print_r($result['review']);
} else {
  echo "Error creating/updating review: " . PHP_EOL;
  echo $result['message'] . PHP_EOL;
}

// Example of an error
$result = createOrUpdateReview($productId, $userId, 6, 'Bad Rating'); // Invalid Rating
?>
