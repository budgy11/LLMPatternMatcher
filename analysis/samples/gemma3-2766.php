

<?php

/**
 * User Review Function
 *
 * This function creates and stores user reviews for a product.
 * It includes validation and basic error handling.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username The username of the reviewer.
 * @param string $rating   The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @return bool          True on successful creation, false otherwise.
 */
function createReview(string $productId, string $username, string $rating, string $comment) {
  // Input validation - Very important!
  if (empty($productId)) {
    error_log("Error: Product ID cannot be empty."); // Log error for debugging
    return false;
  }

  if (empty($username)) {
    error_log("Error: Username cannot be empty.");
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Rating must be a number between 1 and 5.");
    return false;
  }

  if (empty($comment)) {
    error_log("Error: Comment cannot be empty.");
    return false;
  }

  //  Database connection -  Replace with your actual database connection details
  //  This is just a placeholder for demonstration purposes.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  try {
    // Prepare the SQL statement
    $stmt = $db->prepare(
      "INSERT INTO reviews (product_id, username, rating, comment, created_at)
       VALUES (:product_id, :username, :rating, :comment, NOW())"
    );

    // Bind parameters
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);

    // Execute the statement
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
      return true;
    } else {
      error_log("Error: Failed to insert review into database: " . print_r($stmt->errorInfo(), true));
      return false;
    }
  } catch (PDOException $e) {
    error_log("Error: Database error: " . $e->getMessage());
    return false;
  }
}


// Example usage:
$productId = "123";
$username = "JohnDoe";
$rating = 4;
$comment = "Great product, highly recommended!";

if (createReview($productId, $username, $rating, $comment)) {
  echo "Review created successfully!";
} else {
  echo "Failed to create review.";
}


?>
