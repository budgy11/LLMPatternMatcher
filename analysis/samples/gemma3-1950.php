

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, review text, and optionally a rating,
 * and saves the review to a database.  It handles input validation and
 * error logging.
 *
 * @param int $userId The ID of the user submitting the review.
 * @param string $reviewText The text content of the review.
 * @param int $rating (Optional) The rating given for the review (1-5). Defaults to null.
 * @return bool True on success, false on failure.
 */
function saveUserReview(int $userId, string $reviewText, int $rating = null) {
  // Input Validation -  Crucial for security and data integrity
  if ($userId <= 0) {
    error_log("Invalid user ID: " . $userId);
    return false;
  }

  if (empty($reviewText)) {
    error_log("Review text cannot be empty.");
    return false;
  }

  if ($rating > 5 || $rating < 1) {
    error_log("Invalid rating. Rating must be between 1 and 5.");
    return false;
  }

  // Database connection (replace with your actual database credentials)
  $dbHost = 'localhost';
  $dbName = 'your_database_name';
  $dbUser = 'your_username';
  $dbPassword = 'your_password';

  try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For error handling
  } catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    return false;
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO reviews (user_id, review_text, rating) VALUES (:userId, :reviewText, :rating)";
  $stmt = $pdo->prepare($sql);

  // Bind the parameters
  $stmt->bindParam(':userId', $userId);
  $stmt->bindParam(':reviewText', $reviewText);
  $stmt->bindParam(':rating', $rating);

  // Execute the query
  if ($stmt->execute()) {
    return true;
  } else {
    error_log("Error executing SQL query: " . print_r($stmt->errorInfo(), true)); // Log the error details
    return false;
  }
}


// Example usage:
$userId = 123;
$review = "This is a great product!";
$rating = 5;

if (saveUserReview($userId, $review, $rating)) {
  echo "Review saved successfully!";
} else {
  echo "Failed to save review.";
}


// Example with only user ID and review text:
$userId = 456;
$review = "The service was excellent.";

if (saveUserReview($userId, $review)) {
  echo "
Review saved successfully!";
} else {
  echo "
Failed to save review.";
}


?>
