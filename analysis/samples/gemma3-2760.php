

<?php

/**
 * User Review Function
 *
 * This function allows you to save user reviews,
 * including the user's name, message, and rating.
 *
 * @param string $username The name of the user submitting the review.
 * @param string $reviewMessage The user's review message.
 * @param int $rating The user's rating (e.g., 1-5).
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(string $username, string $reviewMessage, int $rating)
{
  // Input Validation - VERY IMPORTANT!  This prevents malicious input.
  if (empty($username)) {
    error_log("Error: Username cannot be empty.");  // Log the error for debugging
    return false;
  }
  if (empty($reviewMessage)) {
    error_log("Error: Review message cannot be empty.");
    return false;
  }
  if ($rating < 1 || $rating > 5) {
    error_log("Error: Rating must be between 1 and 5.");
    return false;
  }

  // Sanitize the input to prevent XSS (Cross-Site Scripting) vulnerabilities
  $username = htmlspecialchars($username);
  $reviewMessage = htmlspecialchars($reviewMessage);


  // **Database Interaction - REPLACE WITH YOUR DATABASE LOGIC**
  // This is just an example; adapt to your database and schema.
  try {
    // Create a database connection (replace with your database credentials)
    // Assuming you have a database connection established called $db
    $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // For robust error handling

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO reviews (username, review_message, rating) VALUES (:username, :review_message, :rating)");

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':review_message', $review_message);
    $stmt->bindParam(':rating', $rating);

    // Execute the statement
    $stmt->execute();

    // Return true if successful
    return true;

  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());  // Log the error
    return false;
  }
}

// Example Usage:
$username = "John Doe";
$review = "This product is amazing!";
$rating = 5;

if (saveUserReview($username, $review, $rating)) {
  echo "Review submitted successfully!";
} else {
  echo "Error submitting review.";
}

?>
