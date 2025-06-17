

<?php

/**
 * User Review Function
 *
 * This function takes a user ID, a review text, and an optional rating,
 * and saves the review to a database.
 *
 * @param int $userId The ID of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given for the review (e.g., 1-5).
 *                    Defaults to 0 if not provided.
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveReview(int $userId, string $reviewText, int $rating = 0) {
  // **Important:  Replace this with your actual database connection code.**
  // This is a placeholder for demonstration.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  // Sanitize and validate inputs to prevent SQL injection and data issues.
  $reviewText = trim($reviewText); // Remove leading/trailing whitespace.
  if (empty($reviewText)) {
    return false; // Don't save empty reviews.
  }
  $rating = intval($rating); // Ensure rating is an integer.  Handles cases where $rating is a string.
  if ($rating < 1 || $rating > 5) {
    $rating = 0; // Default to 0 if rating is outside the valid range.
  }


  try {
    $stmt = $db->prepare("INSERT INTO reviews (user_id, review_text, rating) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $reviewText, $rating]);
    return true;
  } catch (PDOException $e) {
    // Handle database errors gracefully.  Log the error for debugging.
    error_log("Error saving review: " . $e->getMessage());
    return false;
  }
}


// Example Usage:
//  This is just example data - replace with your actual application logic.

$userId = 123;
$review = "This product is fantastic!  Highly recommended.";
$rating = 5;

if (saveReview($userId, $review, $rating)) {
  echo "Review saved successfully!
";
} else {
  echo "Error saving review.
";
}

// Example with no rating
$userId = 456;
$review = "Okay product.";
if (saveReview($userId, $review)) {
  echo "Review saved without rating.
";
} else {
    echo "Error saving review without rating.
";
}

?>
