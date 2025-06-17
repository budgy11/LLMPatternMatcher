

<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews
 * for a specific product.  It uses a simple database interaction for demonstration.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id The ID of the user submitting the review.
 * @param string $rating The rating provided by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $db_connection PDO connection to your database.
 *
 * @return array An array containing:
 *   - success: True if the operation was successful, False otherwise.
 *   - message: A message indicating the outcome of the operation.
 *   - review_id: The ID of the newly created review (if successful).
 *
 */
function create_review(string $product_id, string $user_id, string $rating, string $comment, PDO $db_connection): array
{
  // Input validation (Important for security and data integrity)
  if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
    return [
      'success' => false,
      'message' => 'Error: All fields are required.',
      'review_id' => null
    ];
  }

  if (!preg_match('/^[1-5][0-9]*$/', $rating)) {
    return [
      'success' => false,
      'message' => 'Error: Rating must be a number between 1 and 5.',
      'review_id' => null
    ];
  }
    
  // SQL Injection Prevention (IMPORTANT!) - Use Prepared Statements
  $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) 
          VALUES (:product_id, :user_id, :rating, :comment)";

  try {
    $stmt = $db_connection->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    $stmt->execute();
    $review_id = $db_connection->lastInsertId();

    return [
      'success' => true,
      'message' => 'Review created successfully.',
      'review_id' => $review_id
    ];
  } catch (PDOException $e) {
    // Handle database errors appropriately (logging, user-friendly message)
    return [
      'success' => false,
      'message' => 'Error: ' . $e->getMessage(),
      'review_id' => null
    ];
  }
}


// Example Usage (assuming you have a PDO database connection $db_connection)
//
// $product_id = '123';
// $user_id = 'user456';
// $rating = '5';
// $comment = 'Great product!';

// $result = create_review($product_id, $user_id, $rating, $comment, $db_connection);

// if ($result['success']) {
//   echo "Review created! Review ID: " . $result['review_id'];
// } else {
//   echo "Error: " . $result['message'];
// }

?>
