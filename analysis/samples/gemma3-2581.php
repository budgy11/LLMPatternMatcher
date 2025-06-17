

<?php

/**
 * User Review Function
 *
 * This function allows you to save user reviews with basic details.
 * It includes validation to ensure data integrity and handles errors.
 *
 * @param string $username      The user's name or identifier.
 * @param string $rating        The rating (e.g., 1-5 stars).
 * @param string $comment       The user's comment or feedback.
 * @param string $review_id  (Optional) Unique ID for the review.  If not provided, one will be generated.
 *
 * @return array  An array containing:
 *                - 'success' => true if the review was saved successfully.
 *                - 'message' => A message describing the outcome.
 *                - 'review_id' => The ID of the newly created review (if applicable).
 *
 * @return array  Returns an empty array on failure with an error message.
 */
function saveUserReview(string $username, string $rating, string $comment, string $review_id = null) {
  // Input Validation
  if (empty($username)) {
    return array('success' => false, 'message' => 'Username cannot be empty.', 'review_id' => null);
  }

  if (empty($rating)) {
    return array('success' => false, 'message' => 'Rating cannot be empty.', 'review_id' => null);
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return array('success' => false, 'message' => 'Rating must be a number between 1 and 5.', 'review_id' => null);
  }

  if (empty($comment)) {
    return array('success' => false, 'message' => 'Comment cannot be empty.', 'review_id' => null);
  }

  //  Database connection (Replace with your actual database credentials)
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare(
      "INSERT INTO reviews (username, rating, comment, created_at) VALUES (:username, :rating, :comment, NOW())"
    );

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    $stmt->execute();

    // Get the ID of the newly created review
    $review_id = $db->lastInsertId();

    return array('success' => true, 'message' => 'Review saved successfully.', 'review_id' => $review_id);

  } catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage()); // Log the error for debugging
    return array('success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'review_id' => null);
  }
}


// Example Usage:

// Save a review with a provided ID
$review_data = saveUserReview('John Doe', 4, 'Great product!', '123');
print_r($review_data);

// Save a review without a provided ID (ID will be auto-generated)
$review_data = saveUserReview('Jane Smith', 5, 'Excellent service!', null);
print_r($review_data);

// Example demonstrating error handling:
$review_data = saveUserReview('', 3, 'Invalid review', null);
print_r($review_data);
?>
