

<?php

/**
 * User Review Function
 *
 * This function creates a user review object and allows for basic validation.
 *
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return array|false  Returns an associative array containing review data, 
 *                       or false if validation fails.
 */
function createReview(string $username, string $rating, string $comment) {
  // Validate input
  if (empty($username)) {
    error_log("Error: Username cannot be empty."); // Log for debugging
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Rating must be a number between 1 and 5.");
    return false;
  }

  if (empty($comment)) {
    error_log("Warning: Comment is empty.  Consider a default comment.");
    $comment = "No comment provided."; // Default comment if empty
  }

  // Create review object
  $review = [
    'username' => $username,
    'rating' => (int)$rating, // Convert to integer for consistency
    'comment' => $comment,
    'timestamp' => date('Y-m-d H:i:s') // Add timestamp
  ];

  return $review;
}


// Example Usage:

// Valid Review
$review1 = createReview("JohnDoe", 4, "Great product! Highly recommended.");
if ($review1) {
  echo "Review 1:
";
  print_r($review1);
} else {
  echo "Review 1 creation failed.
";
}

echo "
";

// Invalid Review (Empty Username)
$review2 = createReview("", 3, "Okay product.");
if (!$review2) {
  echo "Review 2 creation failed (as expected).
";
}

echo "
";

// Invalid Review (Invalid Rating)
$review3 = createReview("JaneSmith", 6, "Good product.");
if (!$review3) {
  echo "Review 3 creation failed (as expected).
";
}

echo "
";

// Valid Review (with empty comment)
$review4 = createReview("PeterJones", 5, "");
if ($review4) {
  echo "Review 4:
";
  print_r($review4);
}
?>
