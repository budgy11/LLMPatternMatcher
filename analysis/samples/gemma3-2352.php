

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product or item.
 * It handles data validation, database interaction (if needed), and basic display.
 *
 * @param string $productId  The ID of the product/item being reviewed.
 * @param string $username   The username of the user submitting the review.
 * @param string $rating    The rating given by the user (e.g., 1-5).
 * @param string $comment    The user's review comment.
 * @param PDO    $pdo      Optional PDO database connection object.  If not provided, assumes a global $db connection.
 *
 * @return array  An array containing:
 *               - 'success': True if the review was successfully created, false otherwise.
 *               - 'message': A message indicating success or the error message.
 */
function createReview($productId, $username, $rating, $comment, $pdo = null) {
  // Data Validation
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

  // Database Interaction (using PDO - best practice)
  try {
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) VALUES (:product_id, :username, :rating, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();

    return ['success' => true, 'message' => 'Review created successfully!'];

  } catch (PDOException $e) {
    // Handle database errors gracefully -  Don't expose the full error to the user.
    return ['success' => false, 'message' => 'Error creating review: ' . $e->getMessage()];
  }
}


/**
 * Displays a single review.
 *
 * @param array $review  An array representing a single review (e.g., returned by createReview).
 */
function displayReview($review) {
  if ($review['success']) {
    echo "<p><strong>Rating:</strong> " . $review['message'] . "</p>";
  } else {
    echo "<p style='color:red;'>Error: " . $review['message'] . "</p>";
  }
}


/**
 * Example Usage (For demonstration purposes)
 */
// Example 1: Successful Review
$reviewData = createReview('123', 'JohnDoe', 4, 'Great product! I highly recommend it.', $db);
displayReview($reviewData);

// Example 2:  Invalid Rating
$reviewData = createReview('456', 'JaneSmith', 6, 'Awesome!', $db);
displayReview($reviewData);

// Example 3:  Empty Comment
$reviewData = createReview('789', 'PeterPan', 3, '', $db);
displayReview($reviewData);

?>
