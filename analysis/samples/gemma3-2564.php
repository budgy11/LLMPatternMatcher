

<?php

/**
 * Function to handle user reviews.
 *
 * This function allows you to process user reviews, storing them in a database
 * or an array for later use.  It handles data validation and basic formatting.
 *
 * @param string $username  The username of the user submitting the review.
 * @param string $rating  The rating provided by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param string $review_id (Optional)  Unique identifier for the review.
 * @return bool|string  Returns true on success, or an error message string on failure.
 */
function process_review(string $username, string $rating, string $comment, string $review_id = "") {
  // 1. Validation
  if (empty($username)) {
    return "Error: Username cannot be empty.";
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return "Error: Rating must be a number between 1 and 5.";
  }

  if (empty($comment)) {
    return "Error: Comment cannot be empty.";
  }

  // 2. Data Formatting (Optional - Adapt to your needs)
  $formatted_rating = (int)$rating;  // Convert rating to integer
  $formatted_comment = trim($comment); // Remove leading/trailing whitespace

  // 3. Data Storage (Example:  Storing in an array - Adapt to your database logic)
  $review = [
    'username' => $username,
    'rating' => $formatted_rating,
    'comment' => $formatted_comment,
    'timestamp' => time(), // Add a timestamp for tracking
    'review_id' => $review_id
  ];

  //  Store the review data (example:  print to the console for demonstration)
  //  In a real application, you would insert this into a database
  //  using a database query.
  echo "Review received from: " . $username . "
";
  echo "Rating: " . $formatted_rating . "
";
  echo "Comment: " . $formatted_comment . "
";
  echo "Timestamp: " . date("Y-m-d H:i:s", $review['timestamp']) . "
";

  return true; // Indicate success
}

// Example Usage:
$result = process_review("JohnDoe", 4, "Great product!  I really liked it.", "review_123");
if ($result === true) {
  echo "Review submitted successfully!
";
} else {
  echo $result . "
"; // Display the error message
}


$result = process_review("JaneSmith", 2, "Okay, but could be better.", "review_456");
if ($result === true) {
  echo "Review submitted successfully!
";
} else {
  echo $result . "
";
}

$result = process_review("TestUser", 6, "Invalid rating", "review_789");
if ($result === true) {
  echo "Review submitted successfully!
";
} else {
  echo $result . "
";
}
?>
