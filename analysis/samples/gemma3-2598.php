

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $userId The ID of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param array $reviews An array to store existing reviews.
 *
 * @return array An updated array of reviews with the new review added.
 */
function createAndDisplayReview(string $productName, string $userId, string $reviewText, array &$reviews) {
  // Validate inputs (basic example - expand this for more robust validation)
  if (empty($reviewText)) {
    return $reviews; // Don't add empty reviews
  }

  // Create the review object (could be an object or array, this is just an example)
  $review = [
    'id' => count($reviews) + 1,
    'userId' => $userId,
    'productName' => $productName,
    'reviewText' => $reviewText,
    'timestamp' => time()
  ];

  // Add the review to the array
  $reviews[] = $review;

  // Optionally, you could sort the reviews by timestamp
  usort($reviews, function($a, $b) {
    return $a['timestamp'] - $b['timestamp'];
  });

  return $reviews;
}


/**
 * Display Reviews
 *
 * This function formats and displays the reviews.
 *
 * @param array $reviews An array of reviews to display.
 */
function displayReviews(array $reviews) {
  echo "<h2>Reviews for " . $GLOBALS['productName'] . "</h2>"; // Assuming $productName is globally defined

  if (empty($reviews)) {
    echo "<p>No reviews yet!</p>";
    return;
  }

  foreach ($reviews as $review) {
    echo "<h3>Review by User ID: " . $review['userId'] . "</h3>";
    echo "<p>" . htmlspecialchars($review['reviewText']) . "</p>"; // Sanitize HTML output
    echo "<p>Timestamp: " . date('Y-m-d H:i:s', $review['timestamp']) . "</p>";
  }
}


// --- Example Usage ---

// Initialize reviews (an array to store the reviews)
$reviews = [];

//  For demonstration, let's set a global product name
$GLOBALS['productName'] = "Awesome Widget";

// Create some reviews
$reviews = createAndDisplayReview("Awesome Widget", "user123", "This widget is amazing! I highly recommend it.", $reviews);
$reviews = createAndDisplayReview("Awesome Widget", "user456", "Great product, works as advertised.", $reviews);
$reviews = createAndDisplayReview("Awesome Widget", "user123", "Could be better, but not bad.", $reviews);

// Display the reviews
displayReviews($reviews);

?>
