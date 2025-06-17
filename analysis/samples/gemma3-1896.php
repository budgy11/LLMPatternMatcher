

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews for a product or service.
 * It includes validation, sanitization, and basic data handling.
 *
 * @param string $productId  The ID of the product or service being reviewed.
 * @param string $author     The name or username of the reviewer.
 * @param string $rating     The rating given (e.g., 1-5).
 * @param string $comment    The reviewer's comment/feedback.
 * @param array $dbConnection Database connection object.  This is crucial!
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function saveReview(string $productId, string $author, string $rating, string $comment, array $dbConnection) {

  // Validation
  $errors = [];

  // Check if product ID is provided
  if (empty($productId)) {
    $errors[] = "Product ID is required.";
  }

  // Check if author is provided
  if (empty($author)) {
    $errors[] = "Author is required.";
  }

  // Check if rating is a valid number between 1 and 5
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    $errors[] = "Rating must be a number between 1 and 5.";
  }

  // Check if comment is provided
  if (empty($comment)) {
    $errors[] = "Comment is required.";
  }

  // Sanitize input (basic - you might want more robust sanitization)
  $productId = trim($productId);
  $author = trim($author);
  $rating = (int)$rating; // Ensure rating is an integer
  $comment = trim($comment);

  // If there are errors, return false
  if (!empty($errors)) {
    return false;
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO reviews (product_id, author, rating, comment) 
          VALUES (:product_id, :author, :rating, :comment)";

  // Prepare the statement
  $stmt = $dbConnection->prepare($sql);

  // Bind the parameters
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':author', $author);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $comment);

  // Execute the statement
  if ($stmt->execute()) {
    return true;
  } else {
    // Handle errors (logging, database errors, etc.)
    error_log("Error saving review: " . $stmt->error);
    return false;
  }
}


// Example usage (assuming you have a database connection object $conn)
/*
$product_id = "123";
$author = "John Doe";
$rating = 4;
$comment = "Great product, would recommend!";

if (saveReview($product_id, $author, $rating, $comment, $conn)) {
  echo "Review submitted successfully!";
} else {
  echo "Failed to submit review.  Check the error logs.";
}
*/

?>
