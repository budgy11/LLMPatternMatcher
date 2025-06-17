    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="comment">Review:</label><br>
    <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br><br>

    <label for="rating">Rating (1-5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required><br><br>

    <label for="item_id">Item ID (Optional):</label>
    <input type="text" id="item_id" name="item_id"><br><br>

    <input type="submit" value="Submit Review">
</form>


<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userId The ID of the user submitting the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's written review comment.
 * @param int $reviewId (Optional) The ID of the review to update or delete. If not provided, a new review is created.
 *
 * @return array An array containing:
 *   - 'success': true if the operation was successful, false otherwise.
 *   - 'message': A message describing the result of the operation.
 *   - 'reviewId': The ID of the newly created or updated review.
 */
function create_review(string $productId, string $userId, string $rating, string $comment, int $reviewId = 0) {
  // **Validation (IMPORTANT)** -  Add more robust validation here!
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Product ID cannot be empty.', 'reviewId' => 0];
  }
  if (empty($userId)) {
    return ['success' => false, 'message' => 'User ID cannot be empty.', 'reviewId' => 0];
  }
  if (empty($rating)) {
    return ['success' => false, 'message' => 'Rating cannot be empty.', 'reviewId' => 0];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Comment cannot be empty.', 'reviewId' => 0];
  }

  // Ensure Rating is an integer between 1 and 5.  This is crucial.
  $rating = intval($rating); // Convert to integer
  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'message' => 'Rating must be between 1 and 5.', 'reviewId' => 0];
  }



  // **Database Interaction - Replace with your database connection logic**
  // This is a placeholder.  You'll need to replace this with
  // your actual database connection and query logic.

  // Example using a hypothetical database connection:
  $db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");

  try {
    $stmt = $db->prepare(
      "INSERT INTO reviews (productId, userId, rating, comment) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$productId, $userId, $rating, $comment]);

    $reviewId = $db->lastInsertId(); // Get the ID of the newly inserted row

    return ['success' => true, 'message' => 'Review created successfully.', 'reviewId' => $reviewId];

  } catch (PDOException $e) {
    // Handle database errors appropriately (logging, etc.)
    return ['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'reviewId' => 0];
  }
}


/**
 * Example Usage:
 */
// Create a new review
$result = create_review("123", "user123", "5", "Great product! Highly recommended.");
print_r($result);

// Update an existing review (assuming $reviewId = 1)
$result = create_review("456", "user456", "4", "Good, but could be better.", 1);
print_r($result);

// Error handling examples:
$result = create_review("", "user1", "3", "Review", 2); // Empty Product ID
print_r($result);
?>
