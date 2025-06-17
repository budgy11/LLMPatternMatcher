

<?php

/**
 * User Review Function
 *
 * This function takes user input (review text, rating, and optionally, user information)
 * and stores the review in a database or returns it as a structured object.
 *
 * @param string $reviewText - The text of the review.
 * @param int $rating - The rating given by the user (e.g., 1-5 stars).
 * @param array $userData - (Optional) An array containing user information (e.g., user_id, username).
 * @param string $dbConnection - (Optional)  A database connection object.
 * @return array|object - Returns an array with review data, or a custom object if configured.
 *                        Returns null on error.
 */
function create_review(string $reviewText, int $rating, array $userData = [], string $dbConnection = null)
{
  // **Input Validation - IMPORTANT!**
  if (empty($reviewText)) {
    error_log("Review text cannot be empty."); // Log the error for debugging.  Don't display to the user directly.
    return null;
  }

  if ($rating < 1 || $rating > 5) { // Adjust rating range as needed
    error_log("Rating must be between 1 and 5.");
    return null;
  }

  if (empty($userData['user_id'])) {
    error_log("User ID cannot be empty.");
    return null;
  }


  // **Database Interaction (Example - SQLite)**
  // Replace with your database connection and query logic.
  try {
    $db = $dbConnection ?: new SQLite3('reviews.db'); // Use provided connection or create a new one
    $stmt = $db->prepare('INSERT INTO reviews (user_id, review_text, rating, created_at) VALUES (:user_id, :review_text, :rating, :created_at)');
    $stmt->bindValue(':user_id', $userData['user_id']);
    $stmt->bindValue(':review_text', $reviewText);
    $stmt->bindValue(':rating', $rating);
    $stmt->bindValue(':created_at', date('Y-m-d H:i:s')); // Set the timestamp

    $result = $stmt->execute();

    $review_id = $db->lastInsertId(); // Get the ID of the newly created review.

    $db->close();

    return [
      'review_id' => $review_id,
      'user_id' => $userData['user_id'],
      'review_text' => $reviewText,
      'rating' => $rating,
      'created_at' => date('Y-m-d H:i:s'), // Return timestamp as a string
    ];

  } catch (Exception $e) {
    error_log("Database error: " . $e->getMessage()); // Log the full error message for debugging.
    return null; // Handle database errors gracefully.
  }
}

// **Example Usage:**
// Assume you have a database connection object named $conn

// Sample Data
$user_id = 123;
$review = "This product is amazing!  I highly recommend it.";
$rating = 5;

// Create the review
$new_review = create_review($review, $rating, ['user_id' => $user_id]);

if ($new_review) {
  echo "Review created successfully! Review ID: " . $new_review['review_id'] . "<br>";
} else {
  echo "Failed to create review.<br>";
}

// Example of a failed usage (empty review text)
$empty_review = create_review("", 3);
if ($empty_review === null) {
  echo "Review creation failed (empty review).<br>";
}

?>
