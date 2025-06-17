

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param int $itemId The ID of the item being reviewed (e.g., product ID, movie ID).
 * @param array $reviews An array to store reviews (default empty).
 *
 * @return array  An updated array of reviews. Returns the original array if there's an error.
 */
function create_review(string $username, string $reviewText, int $itemId, array &$reviews = []) {

  // Input Validation -  Important for security and data integrity
  if (empty($username) || empty($reviewText) || $itemId <= 0) {
    error_log("Invalid review data submitted."); // Log the error for debugging.  Don't display directly to the user.
    return $reviews; // Return the unchanged array.
  }

  // Sanitize input to prevent XSS and SQL Injection
  $username = htmlspecialchars($username);
  $reviewText = htmlspecialchars($reviewText);

  // Create the review object (you can customize this)
  $review = [
    'username' => $username,
    'review' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s'), // Add a timestamp for ordering
    'itemId' => $itemId,
  ];

  // Add the review to the array.  Use 'array_push' for efficient appending.
  array_push($reviews, $review);

  return $reviews;
}


/**
 * Display Reviews Function
 *
 *  Displays the reviews associated with a given item ID.
 *
 * @param array $reviews An array of review objects.
 * @param int $itemId The ID of the item being reviewed.
 *
 * @return void  Prints the reviews to the screen.
 */
function display_reviews(array $reviews, int $itemId) {
  echo "<h2>Reviews for Item ID: " . $itemId . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['username'] . ":</strong> " . $review['review'] . "<br>";
    echo "<em>Timestamp: " . $review['timestamp'] . "</em>";
    echo "</li>";
  }
  echo "</ul>";
}


// Example Usage:

// Initialize an empty array to store reviews
$reviews = [];

// Create some reviews
$reviews = create_review("John Doe", "Great product!  Highly recommended.", 123);
$reviews = create_review("Jane Smith", "It was okay, but could be better.", 123);
$reviews = create_review("Peter Jones", "Excellent value for money.", 456);

// Display the reviews
display_reviews($reviews, 123);

//Display reviews for item 456
display_reviews($reviews, 456);


?>
