

<?php

/**
 * User Review Function
 *
 * This function allows users to submit reviews and displays them.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username  The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param array  $reviews  (Optional) An array of existing reviews to display.
 * 
 * @return array  An array containing the updated reviews and a success message.
 */
function handleReview(string $productId, string $username, string $reviewText, array &$reviews = []): array {
  // Validate input (add more validation as needed)
  if (empty($reviewText)) {
    return ['status' => 'error', 'message' => 'Review text cannot be empty.'];
  }

  // Create a review object (could be more sophisticated if needed)
  $review = [
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'timestamp' => time(), // Add timestamp for ordering and tracking
  ];

  // Add the new review to the array
  $reviews[] = $review;

  // Sort reviews by timestamp (most recent first)
  usort($reviews, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
  });

  return ['status' => 'success', 'message' => 'Review submitted successfully!', 'reviews' => $reviews];
}

/**
 * Display Reviews Function
 *
 *  Displays the reviews in a formatted manner.
 *
 * @param array $reviews An array of reviews to display.
 */
function displayReviews(array $reviews) {
  echo "<h2>Reviews for Product ID: " . $reviews[0]['productId'] . "</h2>"; // Display Product ID

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  foreach ($reviews as $review) {
    echo "<div class='review'>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($review['username']) . "</p>";
    echo "<p><strong>Rating:</strong>" . $review['timestamp'] . "</p>"; // Display timestamp as a proxy for rating
    echo "<p>" . htmlspecialchars($review['reviewText']) . "</p>";
    echo "</div>";
  }
}


// --- Example Usage ---

// Initialize reviews array (optional)
$reviews = [];

// Simulate a user submitting a review
$result = handleReview('P123', 'JohnDoe', 'This is a fantastic product! I highly recommend it.');

if ($result['status'] === 'success') {
  echo "<p>" . $result['message'] . "</p>";
  // Display the updated reviews
  displayReviews($result['reviews']);
} else {
  echo "<p>Error submitting review: " . $result['message'] . "</p>";
}

//Example 2 - submitting another review
$result = handleReview('P123', 'JaneSmith', 'It could be better, but overall pretty good.');

if ($result['status'] === 'success') {
  echo "<p>" . $result['message'] . "</p>";
  // Display the updated reviews
  displayReviews($result['reviews']);
} else {
  echo "<p>Error submitting review: " . $result['message'] . "</p>";
}

?>
