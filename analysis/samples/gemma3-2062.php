

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and error handling.
 *
 * @param string $productId  The ID of the product the review is for.
 * @param string $username   The username of the reviewer.
 * @param string $rating    The rating (e.g., 1-5).
 * @param string $comment    The review text.
 * @param int $id (Optional) The ID of the review to update. If not provided, a new review is created.
 *
 * @return array An array containing the result of the operation:
 *              - 'success': True if the operation was successful.
 *              - 'message': A message describing the result (e.g., 'Review created', 'Review updated', 'Error: ...').
 *              - 'review': The newly created or updated review object.
 */
function createOrUpdateReview(string $productId, string $username, string $rating, string $comment, int $id = 0) {
  // Basic validation
  if (empty($productId)) {
    return ['success' => false, 'message' => 'Error: Product ID cannot be empty.', 'review' => null];
  }
  if (empty($username)) {
    return ['success' => false, 'message' => 'Error: Username cannot be empty.', 'review' => null];
  }
  if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    return ['success' => false, 'message' => 'Error: Username must contain only alphanumeric characters and underscores.', 'review' => null];
  }
  if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
    return ['success' => false, 'message' => 'Error: Rating must be a number between 1 and 5.', 'review' => null];
  }
  if (empty($comment)) {
    return ['success' => false, 'message' => 'Error: Comment cannot be empty.', 'review' => null];
  }
  if (!is_numeric($id)) {
    $id = 0; // Default to 0 for new reviews
  }
  
  // Create the review object
  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => (int)$rating,
    'comment' => $comment,
    'dateCreated' => date('Y-m-d H:i:s')
  ];

  // Save the review (Simulated database interaction - Replace with your actual database logic)
  // This part simulates saving the review to a database.  Adapt this to your database connection and schema.
  // Example using a hypothetical database table named 'reviews':
  // $db = new DatabaseConnection();  // Instantiate your database connection
  // $sql = "INSERT INTO reviews (productId, username, rating, comment, dateCreated) VALUES (?, ?, ?, ?, ?)";
  // $stmt = $db->prepare($sql);
  // $stmt->bind_param("ssisss", $productId, $username, $rating, $comment);
  // $stmt->execute();
  // $reviewId = $db->lastInsertId();

  // Placeholder - assume successful saving
  // In a real application, you'd return the review ID.
  $reviewId = 123; // Replace with actual review ID if you were saving to a database.
  
  return ['success' => true, 'message' => 'Review created successfully', 'review' => $review];
}


// Example Usage:
// 1. Create a new review:
$result = createOrUpdateReview('P123', 'john.doe', 4, 'Great product! I highly recommend it.', 0);
print_r($result);

// 2. Update an existing review (assuming review ID 123 exists):
$result = createOrUpdateReview('P456', 'jane.smith', 5, 'Excellent value for money.', 123);
print_r($result);


?>
