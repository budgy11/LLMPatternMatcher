

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 *
 * @param string $productId  The unique identifier for the product.
 * @param string $username   The user's name (for display).
 * @param string $rating     The user's rating (e.g., 1-5 stars).
 * @param string $comment    The user's review text.
 * @param array  $reviews   (Optional) An array to store existing reviews (for persistence).
 *
 * @return array  An updated array of reviews.
 */
function storeUserReview(string $productId, string $username, string $rating, string $comment, array &$reviews = []) {
  // Validate inputs (basic example - you might want more robust validation)
  if (empty($productId)) {
    return $reviews; // Or throw an exception - depends on your error handling
  }
  if (empty($username)) {
    $username = 'Anonymous';
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    $rating = 3; // Default rating if invalid
  }
  if (empty($comment)) {
    $comment = 'No comment provided.';
  }

  // Create a review object (you can adapt this to your data structure)
  $review = [
    'productId' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'timestamp' => time() // Add a timestamp for sorting and tracking
  ];

  // Add the review to the array
  $reviews[] = $review;

  // Sort reviews by timestamp (most recent first) - optional
  usort($reviews, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
  });

  return $reviews;
}



/**
 * Display User Reviews
 *
 * This function displays a list of user reviews for a given product.
 *
 * @param array $reviews An array of reviews to display.
 */
function displayReviews(array $reviews) {
  echo "<h2>Reviews for Product ID: " .  implode(",", array_map('getKey', $reviews)) . "</h2>";
  echo "<ul>";

  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['username'] . ":</strong> " . $review['comment'] . " (" . $review['rating'] . "/5)";
    echo "</li>";
  }

  echo "</ul>";
}


// ------------------- Example Usage -------------------

// Initialize an empty array to store reviews
$productReviews = [];

// Store some reviews
$productReviews = storeUserReview('P123', 'John Doe', 4, 'Great product, works as expected!', $productReviews);
$productReviews = storeUserReview('P123', 'Jane Smith', 5, 'Excellent quality and fast shipping!', $productReviews);
$productReviews = storeUserReview('P456', 'Peter Jones', 2, 'It was okay, but not great.', $productReviews);
$productReviews = storeUserReview('P123', 'Alice Brown', 1, 'Not what I expected.', $productReviews);


// Display the reviews
displayReviews($productReviews);

?>
