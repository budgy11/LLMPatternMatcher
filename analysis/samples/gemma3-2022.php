

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews with basic details.
 *
 * @param string $user_id  The ID of the user submitting the review.
 * @param string $product_id The ID of the product the review is for.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return bool True on success, false on failure.
 */
function storeUserReview(string $user_id, string $product_id, string $rating, string $comment)
{
  // Validate inputs - Important for security and data integrity
  if (empty($user_id) || empty($product_id) || empty($rating) || empty($comment)) {
    error_log("Error: Missing required review fields."); // Log for debugging
    return false;
  }

  // Validate rating (ensure it's a number and within a reasonable range)
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Invalid rating provided.");
    return false;
  }

  //  *** Database Interaction - Replace with your actual database code ***
  try {
    // Example using a (simplified) database connection
    //  (This is illustrative, adapt to your database setup!)
    $db = new PDO("mysql:host=localhost;dbname=your_database", "your_username", "your_password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    $result = $stmt->execute();

    if ($result) {
      //  You might want to return the ID of the newly inserted review
      return true;
    } else {
      // Log errors to help debug database issues
      error_log("Error: Failed to insert review into database: " . print_r($stmt->errorInfo(), true));
      return false;
    }

  } catch (PDOException $e) {
    // Handle database connection errors gracefully
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}


// *** Example Usage ***
//  (For demonstration purposes only - don't execute this directly in a web environment)
$userId = "user123";
$productId = "product456";
$rating = 4;
$comment = "Great product, highly recommended!";

if (storeUserReview($userId, $productId, $rating, $comment)) {
  echo "Review submitted successfully!";
} else {
  echo "Failed to submit review.";
}

?>
