

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, review text, rating, and optionally a date,
 * and stores the review in a database.
 *
 * @param int    $userId      The ID of the user submitting the review.
 * @param string $reviewText  The text of the review.
 * @param int    $rating      The rating given by the user (e.g., 1-5 stars).
 * @param string|null $date     Optional: The date of the review. If null, the current date is used.
 * @return bool  True if the review was successfully saved, false otherwise.
 */
function saveReview($userId, $reviewText, $rating, $date = null) {
  // Input validation - crucial for security and data integrity
  if (!is_numeric($userId)) {
    error_log("Invalid user ID: " . $userId);
    return false;
  }
  if (empty($reviewText)) {
    error_log("Review text cannot be empty.");
    return false;
  }
  if (!is_int($rating) || $rating < 1 || $rating > 5) {
    error_log("Invalid rating: " . $rating);
    return false;
  }

  // Prepare the database query
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password'); // Replace with your database credentials
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $date_str = $date ?? date('Y-m-d H:i:s'); // Use current date if $date is null

  $stmt = $db->prepare("INSERT INTO reviews (user_id, review_text, rating, review_date) VALUES (:user_id, :review_text, :rating, :review_date)");

  // Bind the parameters to prevent SQL injection
  $stmt->bindParam(':user_id', $userId);
  $stmt->bindParam(':review_text', $reviewText);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':review_date', $date_str);

  try {
    $stmt->execute();
    return true;
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage (Illustrative - Replace with your form handling)
// This is just to demonstrate how you might call this function.
// You'd typically get the data from a form submission.
/*
$userId = 123;
$review = "This is a fantastic product!";
$starRating = 5;
$reviewDate = date('Y-m-d');

if (saveReview($userId, $review, $starRating, $reviewDate)) {
  echo "Review submitted successfully!";
} else {
  echo "Error submitting review.";
}
*/


//  A simple example of a basic form to submit the review.
//  This is a very basic HTML form and needs to be integrated with your backend.
?>
