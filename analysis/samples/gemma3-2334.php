

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating The rating given by the user (e.g., 1-5).
 * @param array $reviews (Optional) An array of existing reviews to store.  If null, a new array is created.
 *
 * @return array|null  Returns the updated array of reviews, or null if an error occurs.
 */
function storeUserReview(string $productId, string $username, string $reviewText, int $rating, array $reviews = null) {
  // Basic validation (add more robust validation as needed)
  if (empty($productId)) {
    error_log("Error: Product ID cannot be empty.");
    return null;
  }
  if (empty($username)) {
    error_log("Error: Username cannot be empty.");
    return null;
  }
  if (empty($reviewText)) {
    error_log("Error: Review text cannot be empty.");
    return null;
  }
  if ($rating < 1 || $rating > 5) {
    error_log("Error: Rating must be between 1 and 5.");
    return null;
  }

  // Create a new review object
  $review = [
    'productId' => $productId,
    'username' => $username,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'timestamp' => time() // Add timestamp for sorting/ordering
  ];

  // If a reviews array was provided, append it.  Otherwise create a new one.
  if ($reviews === null) {
    $reviews = [];
  }

  $reviews[] = $review;
  return $reviews;
}


/**
 * Display User Reviews
 *
 * This function displays a list of user reviews for a given product.
 *
 * @param string $productId The ID of the product to retrieve reviews for.
 * @param array $reviews An array of user reviews.
 *
 * @return void Prints the reviews to the console (can be modified to output to HTML etc.).
 */
function displayReviews(string $productId, array $reviews) {
  echo "<h2>Reviews for Product ID: " . $productId . "</h2>";
  if (empty($reviews)) {
    echo "<p>No reviews yet!</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<strong>" . $review['username'] . ":</strong> " . $review['reviewText'] . " (Rating: " . $review['rating'] . ") - " . date("Y-m-d H:i:s", strtotime($review['timestamp'])); // Format timestamp for readability
    echo "</li>";
  }
  echo "</ul>";
}


// Example Usage:
// --------------------------------------------------

// Create a new reviews array (or use an existing one)
$productReviews = storeUserReview("P123", "John Doe", "Great product!", 5);
if ($productReviews !== null) {
  // Append another review
  $productReviews = storeUserReview("P123", "Jane Smith", "Works well.", 4);
  if ($productReviews !== null) {
    // Display the reviews
    displayReviews("P123", $productReviews);
  }
} else {
  echo "<p>Error storing review.</p>";
}
?>
