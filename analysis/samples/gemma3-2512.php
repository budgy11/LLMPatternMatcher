

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 * It includes basic validation and sanitization.
 *
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text content of the review.
 * @return array An array containing:
 *   - 'success': True if the review was successfully submitted, false otherwise.
 *   - 'message': A message indicating the status of the review submission.
 *   - 'review':  The submitted review data if successful, or an empty array if not.
 */
function submit_review(string $user_name, string $review_text) {
  // Input validation - basic checks
  if (empty($user_name)) {
    return ['success' => false, 'message' => 'User name cannot be empty.', 'review' => []];
  }

  if (empty($review_text)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.', 'review' => []];
  }

  // Sanitize input -  Important for security
  $user_name = htmlspecialchars($user_name);
  $review_text = htmlspecialchars($review_text);

  //  You might want to add more sophisticated sanitization here,
  //  such as stripping HTML tags or limiting review length.

  // Store the review (in this example, we'll just return it directly)
  //  In a real application, you would save this to a database.
  $review = [
    'user_name' => $user_name,
    'review_text' => $review_text,
    'timestamp' => time() // Add a timestamp for tracking
  ];

  return ['success' => true, 'message' => 'Review submitted successfully!', 'review' => $review];
}



/**
 * Display Reviews (Example)
 * This function demonstrates how to display the reviews.
 *
 * @param array $reviews An array of review data (as returned by submit_review).
 */
function display_reviews(array $reviews) {
  echo "<h2>Reviews:</h2>";

  if (empty($reviews['review'])) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  foreach ($reviews['review'] as $review) {
    echo "<div class='review'>";
    echo "<strong>" . $review['user_name'] . "</strong> - ";
    echo "<p>" . $review['review_text'] . "</p>";
    echo "<p><em>Timestamp: " . date("Y-m-d H:i:s", $review['timestamp']) . "</em></p>";
    echo "</div>";
  }
}


// Example Usage:
$user_name = "John Doe";
$review_text = "This product is amazing!  I highly recommend it.";

$result = submit_review($user_name, $review_text);

if ($result['success']) {
  echo "<p>Review submitted successfully!</p>";
  display_reviews($result);
} else {
  echo "<p>Error submitting review: " . $result['message'] . "</p>";
}

?>
