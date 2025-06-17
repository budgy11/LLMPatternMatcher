

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It includes basic validation and saves reviews to a database.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_id The ID of the user writing the review.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return bool True on success, false on failure (e.g., invalid input).
 */
function create_review(string $product_id, string $user_id, string $rating, string $comment) {
  // Validation: Basic input validation
  if (empty($product_id) || empty($user_id) || empty($rating) || empty($comment)) {
    error_log("Invalid review input: Missing product ID, user ID, rating, or comment.");  // Log for debugging
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Invalid rating provided. Rating must be a number between 1 and 5.");
    return false;
  }

  // Database connection (Replace with your actual database connection details)
  $db_host = "localhost";
  $db_user = "your_db_user";
  $db_pass = "your_db_password";
  $db_name = "your_db_name";

  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    return false;
  }

  // Prepare SQL statement
  $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)";
  $stmt = $pdo->prepare($sql);

  // Bind parameters
  $stmt->bindParam(':product_id', $product_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $comment);

  // Execute the query
  try {
    $stmt->execute();
    return true; // Success!
  } catch (PDOException $e) {
    error_log("Database error during review creation: " . $e->getMessage());
    return false;
  } finally {
    $pdo = null; // Close the database connection
  }
}

// Example Usage:
// $product_id = "123";
// $user_id = "456";
// $rating = 4;
// $comment = "This product is great!";

// if (create_review($product_id, $user_id, $rating, $comment)) {
//   echo "Review created successfully!";
// } else {
//   echo "Failed to create review.";
// }
?>
