

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view user reviews for a specific product or item.
 *
 * @param string $productId The unique identifier of the product or item being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param array $reviews An optional array of existing reviews to display.
 *
 * @return array An associative array containing the new review (if submitted) 
 *              and all existing reviews.
 */
function createAndDisplayReviews(string $productId, string $username, string $reviewText, array $reviews = []) {
  // Validation - Basic Input Validation (Expand as needed)
  if (empty($username)) {
    return ['error' => 'Username cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['error' => 'Review text cannot be empty.'];
  }

  // Add the new review
  $newReview = [
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'timestamp' => date('Y-m-d H:i:s'),
  ];
  $reviews[] = $newReview;

  // Sort reviews by timestamp (newest first) -  Useful for displaying recent reviews
  usort($reviews, function($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
  });

  return $reviews;
}


// Example Usage:
$product_id = 'product123';

// Submit a new review
$reviews = createAndDisplayReviews($product_id, 'JohnDoe', 'This product is amazing!  I highly recommend it.');

if (isset($reviews['error'])) {
  echo "Error: " . $reviews['error'] . "<br>";
} else {
  echo "<h2>Reviews for Product: " . $product_id . "</h2>";
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li><strong>" . $review['username'] . ":</strong> " . $review['reviewText'] . " (" . $review['timestamp'] . ")</li>";
  }
  echo "</ul>";
}


// Example with an empty review
$reviews = createAndDisplayReviews($product_id, 'JaneSmith', "");
if (isset($reviews['error'])) {
    echo "Error: " . $reviews['error'] . "<br>";
}

?>
